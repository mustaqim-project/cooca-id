<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiTokenLot extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ai_token_lots';

    public const STATUS_ACTIVE    = 'active';
    public const STATUS_DEPLETED  = 'depleted';
    public const STATUS_EXPIRED   = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    public const SOURCE_TOPUP            = 'topup';
    public const SOURCE_SUBSCRIPTION     = 'subscription';
    public const SOURCE_BONUS            = 'bonus';
    public const SOURCE_PROMOTION        = 'promotion';
    public const SOURCE_REFUND           = 'refund';
    public const SOURCE_ADMIN_ADJUSTMENT = 'admin_adjustment';

    protected $fillable = [
        'wallet_id',
        'customer_id',
        'license_id',
        'lot_number',
        'name',
        'source_type',
        'source_id',
        'original_tokens',
        'remaining_tokens',
        'used_tokens',
        'purchased_at',
        'starts_at',
        'expires_at',
        'status',
        'metadata',
    ];

    protected $casts = [
        'original_tokens'  => 'integer',
        'remaining_tokens' => 'integer',
        'used_tokens'      => 'integer',
        'purchased_at'     => 'datetime',
        'starts_at'        => 'datetime',
        'expires_at'       => 'datetime',
        'metadata'         => 'array',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(AiWallet::class, 'wallet_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class, 'license_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(AiTokenTransaction::class, 'lot_id');
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(AiUsageLog::class, 'token_lot_id');
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED || $this->expires_at->isPast();
    }

    public function isDepleted(): bool
    {
        return $this->remaining_tokens <= 0 || $this->status === self::STATUS_DEPLETED;
    }

    public function daysUntilExpiration(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        return (int) now()->diffInDays($this->expires_at, false);
    }
}
