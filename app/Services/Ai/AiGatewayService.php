<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Models\AiApiKey;
use App\Models\AiProviderConfig;
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

        $providerConfig = AiProviderConfig::first();
        $configuredModels = $providerConfig ? $providerConfig->getModelsList() : ['gpt-4o-mini', 'gpt-4o'];

        $allowedModels = !empty($planConfig->allowed_models) ? $planConfig->allowed_models : $configuredModels;
        $requestedModel = $payload['model'] ?? ($configuredModels[0] ?? 'gpt-4o-mini');

        // Check if model is allowed on this license or available in Cooca AI Gateway
        if (!in_array($requestedModel, $allowedModels, true) && !in_array($requestedModel, $configuredModels, true)) {
            return $this->errorResponse(403, "Model '{$requestedModel}' tidak tersedia pada layanan Cooca AI Gateway.", [
                'available_models' => $allowedModels,
            ]);
        }

        $cycle = $this->quota->currentCycleFor($license);
        
        if ($this->quota->isExhausted($cycle) && $planConfig->overage_policy === 'hard_stop') {
            $this->metering->logRejected($apiKey, $license, $requestedModel, 'quota_exceeded');
            return $this->errorResponse(429, 'Kuota bulanan token AI Anda telah habis.', [
                'tokens_used' => $cycle->tokens_used,
                'tokens_quota' => $cycle->token_quota,
            ]);
        }

        $started = microtime(true);

        try {
            $provider = $this->providers->resolveFor($requestedModel);
            $providerResponse = $provider->chatCompletion($payload);
        } catch (Throwable $e) {
            $this->metering->logError($apiKey, $license, $requestedModel, $e);
            return $this->errorResponse(502, 'Permintaan ke AI Provider gagal: ' . $e->getMessage());
        }

        $durationMs = (int) ((microtime(true) - $started) * 1000);

        $usage = $providerResponse['usage'] ?? [
            'prompt_tokens' => 0,
            'completion_tokens' => 0,
            'total_tokens' => 0,
        ];
        $this->metering->logSuccess($apiKey, $license, $requestedModel, $usage, $durationMs);
        
        $updatedCycle = $this->quota->increment($cycle, $usage['total_tokens'] ?? 0);

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
