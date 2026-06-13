<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AffiliateCommission extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'affiliate_commissions';

    protected $fillable = [
        'affiliator_id',
        'transaction_id',
        'customer_id',
        'level',
        'gross_amount',
        'commission_percent',
        'commission_amount',
        'status',
        'cleared_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'cleared_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_CLEARED = 'cleared';
    public const STATUS_CANCELLED = 'cancelled';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CLEARED,
            self::STATUS_CANCELLED,
        ];
    }

    public function affiliator(): BelongsTo
    {
        return $this->belongsTo(Affiliator::class, 'affiliator_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCleared(): bool
    {
        return $this->status === self::STATUS_CLEARED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->commission_amount, 0, ',', '.');
    }

    public function getFormattedGrossAttribute(): string
    {
        return 'Rp ' . number_format($this->gross_amount, 0, ',', '.');
    }

    public function getPercentageAttribute(): string
    {
        return number_format($this->commission_percent, 1) . '%';
    }
}
