<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $customer_id
 * @property string $subscription_id
 * @property string $invoice_number
 * @property float $gross_amount
 * @property float $voucher_discount
 * @property float $net_amount
 * @property string|null $voucher_id
 * @property string|null $payment_method
 * @property string|null $payment_gateway
 * @property string|null $midtrans_order_id
 * @property string|null $midtrans_transaction_id
 * @property string|null $midtrans_status
 * @property string $status
 * @property \Carbon\Carbon|null $paid_at
 * @property \Carbon\Carbon|null $failed_at
 * @property \Carbon\Carbon|null $refunded_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
final class Transaction extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'transactions';

    protected $fillable = [
        'customer_id',
        'subscription_id',
        'invoice_number',
        'gross_amount',
        'voucher_discount',
        'net_amount',
        'voucher_id',
        'payment_method',
        'payment_gateway',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_status',
        'status',
        'paid_at',
        'failed_at',
        'refunded_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'voucher_discount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'failed_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PAID,
            self::STATUS_FAILED,
            self::STATUS_REFUNDED,
        ];
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function subscription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function voucher(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function invoice(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Invoice::class, 'transaction_id');
    }

    public function affiliateCommissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AffiliateCommission::class, 'transaction_id');
    }

    public function scopePaid($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopePending($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
