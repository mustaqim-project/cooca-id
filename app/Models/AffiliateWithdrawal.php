<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $affiliator_id
 * @property float $amount
 * @property float $fee
 * @property float $net_amount
 * @property string $withdrawal_method
 * @property string $account_number
 * @property string $account_name
 * @property string $status
 * @property string|null $approved_by
 * @property \Carbon\Carbon|null $approved_at
 * @property \Carbon\Carbon|null $rejected_at
 * @property string|null $rejection_reason
 * @property \Carbon\Carbon|null $paid_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
final class AffiliateWithdrawal extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'affiliate_withdrawals';

    protected $fillable = [
        'affiliator_id',
        'amount',
        'fee',
        'net_amount',
        'withdrawal_method',
        'account_number',
        'account_name',
        'status',
        'approved_by',
        'approved_at',
        'rejected_at',
        'rejection_reason',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public const METHOD_BANK = 'bank';
    public const METHOD_EWALLET = 'ewallet';

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PAID = 'paid';

    public static function getMethods(): array
    {
        return [self::METHOD_BANK, self::METHOD_EWALLET];
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_PAID,
        ];
    }

    public function affiliator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Affiliator::class, 'affiliator_id');
    }

    public function approvedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function scopePending($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePaid($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }
}
