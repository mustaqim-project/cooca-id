<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AiUsageLog extends Model
{
    use HasUuids;

    protected $table = 'ai_usage_logs';

    public $timestamps = false;

    protected $fillable = [
        'ai_api_key_id',
        'license_id',
        'provider',
        'model',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'cost_usd',
        'status',
        'http_status',
        'duration_ms',
        'created_at',
    ];

    protected $casts = [
        'cost_usd' => 'decimal:6',
        'created_at' => 'datetime',
    ];

    public function apiKey(): BelongsTo
    {
        return $this->belongsTo(AiApiKey::class, 'ai_api_key_id');
    }

    public function aiApiKey(): BelongsTo
    {
        return $this->apiKey();
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
}
