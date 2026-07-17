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
        'invoice_id',
        'subscription_plan_id',
        'plan_name',
        'level',
        'gross_amount',
        'commission_percent',
        'commission_amount',
        'status',
        'cleared_at',
        'available_at',
        'invoice_paid_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'cleared_at' => 'datetime',
        'available_at' => 'datetime',
        'invoice_paid_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_REQUESTED = 'requested';
    public const STATUS_CLEARED = 'cleared';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_VOIDED = 'voided';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_AVAILABLE,
            self::STATUS_REQUESTED,
            self::STATUS_CLEARED,
            self::STATUS_CANCELLED,
            self::STATUS_VOIDED,
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

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isRequested(): bool
    {
        return $this->status === self::STATUS_REQUESTED;
    }

    public function isCleared(): bool
    {
        return $this->status === self::STATUS_CLEARED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isVoided(): bool
    {
        return $this->status === self::STATUS_VOIDED;
    }

    /**
     * Check if commission is within holding period (14 days)
     */
    public function isInHoldingPeriod(): bool
    {
        if (!$this->invoice_paid_at) {
            return false;
        }

        return now()->diffInDays($this->invoice_paid_at) < 14;
    }

    /**
     * Check if commission is ready to be available (14 days holding period passed)
     */
    public function isReadyToBeAvailable(): bool
    {
        if (!$this->invoice_paid_at || $this->status !== self::STATUS_PENDING) {
            return false;
        }

        return now()->diffInDays($this->invoice_paid_at) >= 14;
    }

    /**
     * Mark commission as available after holding period
     */
    public function markAsAvailable(): void
    {
        $this->update([
            'status' => self::STATUS_AVAILABLE,
            'available_at' => now(),
        ]);
    }

    /**
     * Mark commission as requested for withdrawal
     */
    public function markAsRequested(): void
    {
        $this->update(['status' => self::STATUS_REQUESTED]);
    }

    /**
     * Mark commission as cleared (withdrawal completed)
     */
    public function markAsCleared(): void
    {
        $this->update([
            'status' => self::STATUS_CLEARED,
            'cleared_at' => now(),
        ]);
    }

    /**
     * Void commission (e.g., due to refund)
     */
    public function markAsVoided(): void
    {
        $this->update(['status' => self::STATUS_VOIDED]);
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
