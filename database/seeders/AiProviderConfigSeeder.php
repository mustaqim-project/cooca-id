<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AiProviderConfig;
use Illuminate\Database\Seeder;

final class AiProviderConfigSeeder extends Seeder
{
    public function run(): void
    {
        $config = AiProviderConfig::first() ?? new AiProviderConfig(['provider' => 'primary']);
        $config->base_url = 'https://r4g77gv.abc-tunnel.us/v1';
        $config->api_key = 'sk-59807b51bc76063d-j7z41y-9843d7eb';
        $config->models = [
            'cx/gpt-5.5-xhigh',
            'cx/gpt-5.5',
            'ag/claude-sonnet-4-6',
            'ag/claude-opus-4-6-thinking',
            'ag/gemini-pro-agent',
        ];
        $config->total_token_quota = 10000000;
        $config->is_active = true;
        $config->save();
    }
}
