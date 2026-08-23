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
        private readonly AiTokenConsumptionService $consumptionService,
        private readonly AiTokenWalletService $walletService,
        private readonly AiProviderResolver $providers,
        private readonly AiUsageMeteringService $metering,
    ) {}

    public function handleChatCompletion(AiApiKey $apiKey, License $license, array $payload): array
    {
        $customer = $license->customer ?? $apiKey->customer;
        if (!$customer) {
            return $this->errorResponse(401, 'Customer account not associated with this license.');
        }

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

        // 1. Check Available AI Token Balance via Wallet
        $availability = $this->consumptionService->checkAvailable($customer, 10);
        if (!$availability['allowed']) {
            $this->metering->logRejected($apiKey, $license, $requestedModel, 'quota_exceeded');

            return $this->errorResponse(429, 'Saldo AI Token Anda telah habis atau tidak mencukupi.', [
                'available_tokens'   => $availability['available_tokens'],
                'estimated_required' => $availability['required_tokens'],
                'shortage'           => $availability['shortage'],
                'topup_url'          => route('customer.ai.usage'),
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
            'prompt_tokens'     => 0,
            'completion_tokens' => 0,
            'total_tokens'      => 0,
        ];

        $totalTokensToConsume = max(1, (int) ($usage['total_tokens'] ?? 0));

        // 2. Consume Tokens using FEFO and record Transaction Ledger + Usage Log
        try {
            $consumptionResult = $this->consumptionService->consumeTokens($customer, $totalTokensToConsume, [
                'provider'          => $this->guessProviderFromModel($requestedModel),
                'model'             => $requestedModel,
                'input_tokens'      => $usage['prompt_tokens'] ?? 0,
                'output_tokens'     => $usage['completion_tokens'] ?? 0,
                'cached_tokens'     => $usage['cached_tokens'] ?? 0,
                'prompt_tokens'     => $usage['prompt_tokens'] ?? 0,
                'completion_tokens' => $usage['completion_tokens'] ?? 0,
                'duration_ms'       => $durationMs,
                'license_id'        => $license->id,
                'api_key_id'        => $apiKey->id,
                'user_identifier'   => $apiKey->name ?? 'API Key',
            ]);

            $remainingBalance = $consumptionResult['remaining_balance'];
        } catch (Throwable $e) {
            \Illuminate\Support\Facades\Log::error('[AiGatewayService] Token consumption error: ' . $e->getMessage());
            $remainingBalance = $customer->getAvailableAiTokens();
        }

        return [
            'payload'                => $providerResponse['body'],
            'status'                 => 200,
            'tokens_used_this_cycle' => $totalTokensToConsume,
            'tokens_remaining'       => $remainingBalance,
            'available_tokens'       => $remainingBalance,
        ];
    }

    private function guessProviderFromModel(string $model): string
    {
        if (str_starts_with($model, 'gpt-') || str_starts_with($model, 'o1') || str_starts_with($model, 'o3')) {
            return 'openai';
        }
        if (str_starts_with($model, 'claude-')) {
            return 'anthropic';
        }
        if (str_starts_with($model, 'gemini-')) {
            return 'google';
        }
        return 'cooca';
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
