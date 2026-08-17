<?php

declare(strict_types=1);

namespace App\Services\Ai\Providers;

use App\Models\AiProviderConfig;
use RuntimeException;

final class AiProviderResolver
{
    private const MODEL_PROVIDER_MAP = [
        'gpt-'    => 'openai',
        'claude-' => 'anthropic',
    ];

    public function resolveFor(string $model): AiProviderInterface
    {
        foreach (self::MODEL_PROVIDER_MAP as $prefix => $providerKey) {
            if (str_starts_with($model, $prefix)) {
                return $this->build($providerKey);
            }
        }

        throw new RuntimeException("No provider mapped for model '{$model}'");
    }

    private function build(string $providerKey): AiProviderInterface
    {
        $config = AiProviderConfig::where('provider', $providerKey)
            ->where('is_active', true)
            ->firstOrFail();

        return match ($providerKey) {
            'openai'    => new OpenAiProvider($config->api_key, $config->base_url),
            'anthropic' => new AnthropicProvider($config->api_key, $config->base_url),
            default     => throw new RuntimeException("Unsupported provider '{$providerKey}'"),
        };
    }
}
