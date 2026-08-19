<?php

declare(strict_types=1);

namespace App\Services\Ai\Providers;

use App\Models\AiProviderConfig;
use RuntimeException;

final class AiProviderResolver
{
    public function resolveFor(string $model = ''): AiProviderInterface
    {
        $config = AiProviderConfig::where('is_active', true)->first();

        if (!$config) {
            $config = AiProviderConfig::first();
            if (!$config || !$config->is_active) {
                throw new RuntimeException("AI Gateway belum aktif atau belum dikonfigurasi. Silakan atur Base URL dan API Key pada Admin AI Console.");
            }
        }

        if (empty($config->base_url)) {
            throw new RuntimeException("Base URL AI Gateway belum diatur.");
        }

        return new OpenAiProvider($config->api_key ?? '', $config->base_url);
    }
}
