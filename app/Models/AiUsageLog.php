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
        'customer_id',
        'ai_api_key_id',
        'license_id',
        'token_lot_id',
        'provider',
        'model',
        'input_tokens',
        'output_tokens',
        'cached_tokens',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'cost_usd',
        'estimated_cost',
        'actual_cost',
        'request_id',
        'user_identifier',
        'status',
        'http_status',
        'duration_ms',
        'created_at',
    ];

    protected $casts = [
        'input_tokens'      => 'integer',
        'output_tokens'     => 'integer',
        'cached_tokens'     => 'integer',
        'prompt_tokens'     => 'integer',
        'completion_tokens' => 'integer',
        'total_tokens'      => 'integer',
        'cost_usd'          => 'decimal:6',
        'estimated_cost'    => 'decimal:6',
        'actual_cost'       => 'decimal:6',
        'created_at'        => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

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

    public function tokenLot(): BelongsTo
    {
        return $this->belongsTo(AiTokenLot::class, 'token_lot_id');
    }
}
