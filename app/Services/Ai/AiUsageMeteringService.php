<?php

declare(strict_types=1);

namespace App\Services\Ai;

use App\Models\AiApiKey;
use App\Models\AiUsageLog;
use App\Models\License;

final class AiUsageMeteringService
{
    public function logSuccess(
        AiApiKey $apiKey,
        License $license,
        string $model,
        array $usage,
        int $durationMs
    ): void {
        AiUsageLog::create([
            'ai_api_key_id' => $apiKey->id,
            'license_id' => $license->id,
            'provider' => $this->guessProviderFromModel($model),
            'model' => $model,
            'prompt_tokens' => $usage['prompt_tokens'] ?? 0,
            'completion_tokens' => $usage['completion_tokens'] ?? 0,
            'total_tokens' => $usage['total_tokens'] ?? 0,
            'status' => 'success',
            'http_status' => 200,
            'duration_ms' => $durationMs,
        ]);
    }

    public function logRejected(
        AiApiKey $apiKey,
        License $license,
        string $model,
        string $reason
    ): void {
        AiUsageLog::create([
            'ai_api_key_id' => $apiKey->id,
            'license_id' => $license->id,
            'provider' => $this->guessProviderFromModel($model),
            'model' => $model,
            'status' => $reason,
            'http_status' => 429,
        ]);
    }

    public function logError(
        AiApiKey $apiKey,
        License $license,
        string $model,
        \Throwable $e
    ): void {
        AiUsageLog::create([
            'ai_api_key_id' => $apiKey->id,
            'license_id' => $license->id,
            'provider' => $this->guessProviderFromModel($model),
            'model' => $model,
            'status' => 'error',
            'http_status' => 502,
        ]);
    }

    private function guessProviderFromModel(string $model): string
    {
        if (str_starts_with($model, 'gpt-')) {
            return 'openai';
        }
        if (str_starts_with($model, 'claude-')) {
            return 'anthropic';
        }
        return 'unknown';
    }
}
