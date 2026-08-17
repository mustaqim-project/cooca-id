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
        'is_active',
    ];

    protected $casts = [
        'api_key' => 'encrypted',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'api_key',
    ];
}
