<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AiUsageCycle extends Model
{
    use HasUuids;

    protected $table = 'ai_usage_cycles';

    protected $fillable = [
        'license_id',
        'cycle_start',
        'cycle_end',
        'tokens_used',
        'token_quota',
    ];

    protected $casts = [
        'cycle_start' => 'date',
        'cycle_end' => 'date',
        'tokens_used' => 'integer',
        'token_quota' => 'integer',
    ];

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
}
