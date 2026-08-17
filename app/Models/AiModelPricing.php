<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

final class AiModelPricing extends Model
{
    use HasUuids;

    protected $table = 'ai_model_pricing';

    protected $fillable = [
        'provider',
        'model',
        'input_price_per_1k',
        'output_price_per_1k',
        'is_active',
    ];

    protected $casts = [
        'input_price_per_1k' => 'decimal:6',
        'output_price_per_1k' => 'decimal:6',
        'is_active' => 'boolean',
    ];
}
