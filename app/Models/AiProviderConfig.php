<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class AiProviderConfig extends Model
{
    use HasUuids;

    protected $table = 'ai_provider_configs';

    protected $fillable = [
        'provider',
        'api_key',
        'base_url',
        'models',
        'total_token_quota',
        'is_active',
    ];

    protected $casts = [
        'api_key' => 'encrypted',
        'models' => 'array',
        'total_token_quota' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'api_key',
    ];

    /**
     * Get array list of configured models or standard defaults.
     *
     * @return array<int, string>
     */
    public function getModelsList(): array
    {
        $raw = $this->getAttribute('models');
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $raw = $decoded;
            }
        }

        if (!empty($raw) && is_array($raw)) {
            $clean = array_values(array_unique(array_filter(array_map('trim', $raw))));
            if (!empty($clean)) {
                return $clean;
            }
        }

        return [
            'cx/gpt-5.5-xhigh',
            'cx/gpt-5.5',
            'ag/claude-sonnet-4-6',
            'ag/claude-opus-4-6-thinking',
            'ag/gemini-pro-agent',
        ];
    }
}
