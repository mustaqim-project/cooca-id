<?php

declare(strict_types=1);

namespace App\Services\Ai\Providers;

use App\Models\AiProviderConfig;
use RuntimeException;

final class AiProviderResolver
{
    private const MODEL_PROVIDER_MAP = [
        'gpt-'      => 'openai',
        'o1-'       => 'openai',
        'o3-'       => 'openai',
        'text-'     => 'openai',
        'claude-'   => 'anthropic',
        'gemini-'   => 'gemini',
        'deepseek-' => 'deepseek',
    ];

    public function resolveFor(string $model): AiProviderInterface
    {
        foreach (self::MODEL_PROVIDER_MAP as $prefix => $providerKey) {
            if (str_starts_with($model, $prefix)) {
                return $this->build($providerKey);
            }
        }

        // Direct provider key match or fallback
        return $this->build('openai');
    }

    private function build(string $providerKey): AiProviderInterface
    {
        $config = AiProviderConfig::where('provider', $providerKey)
            ->where('is_active', true)
            ->first();

        if (!$config) {
            throw new RuntimeException("AI Provider [{$providerKey}] is not configured or is inactive. Please configure it in the Admin AI Console.");
        }

        return match ($providerKey) {
            'openai'    => new OpenAiProvider($config->api_key, $config->base_url),
            'anthropic' => new AnthropicProvider($config->api_key, $config->base_url),
            'gemini'    => new GeminiProvider($config->api_key, $config->base_url),
            'deepseek'  => new DeepSeekProvider($config->api_key, $config->base_url),
            default     => throw new RuntimeException("Unsupported AI provider '{$providerKey}'"),
        };
    }
}
