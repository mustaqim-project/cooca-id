<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Models\AiApiKey;
use App\Models\License;
use App\Services\Ai\Providers\AiProviderResolver;
use Throwable;

final class AiGatewayService
{
    public function __construct(
        private readonly AiQuotaService $quota,
        private readonly AiProviderResolver $providers,
        private readonly AiUsageMeteringService $metering,
    ) {}

    public function handleChatCompletion(AiApiKey $apiKey, License $license, array $payload): array
    {
        $planConfig = $this->quota->planConfigFor($license);

        if (!in_array($payload['model'], $planConfig->allowed_models, true)) {
            return $this->errorResponse(403, "Model '{$payload['model']}' is not available on your current plan.");
        }

        $cycle = $this->quota->currentCycleFor($license);
        
        if ($this->quota->isExhausted($cycle) && $planConfig->overage_policy === 'hard_stop') {
            $this->metering->logRejected($apiKey, $license, $payload['model'], 'quota_exceeded');
            return $this->errorResponse(429, 'Monthly AI token quota exceeded.', [
                'tokens_used' => $cycle->tokens_used,
                'tokens_quota' => $cycle->token_quota,
            ]);
        }

        $provider = $this->providers->resolveFor($payload['model']);
        $started = microtime(true);

        try {
            $providerResponse = $provider->chatCompletion($payload);
        } catch (Throwable $e) {
            $this->metering->logError($apiKey, $license, $payload['model'], $e);
            return $this->errorResponse(502, 'AI provider request failed. Please try again.');
        }

        $durationMs = (int) ((microtime(true) - $started) * 1000);

        $usage = $providerResponse['usage'];
        $this->metering->logSuccess($apiKey, $license, $payload['model'], $usage, $durationMs);
        
        $updatedCycle = $this->quota->increment($cycle, $usage['total_tokens']);

        return [
            'payload' => $providerResponse['body'],
            'status' => 200,
            'tokens_used_this_cycle' => $updatedCycle->tokens_used,
            'tokens_remaining' => max(0, $updatedCycle->token_quota - $updatedCycle->tokens_used),
        ];
    }

    private function errorResponse(int $status, string $message, array $extra = []): array
    {
        return [
            'payload' => ['error' => array_merge(['message' => $message], $extra)],
            'status' => $status,
            'tokens_used_this_cycle' => 0,
            'tokens_remaining' => 0,
        ];
    }
}
