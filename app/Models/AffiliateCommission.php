<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $affiliator_id
 * @property string $transaction_id
 * @property string $customer_id
 * @property int $level
 * @property float $gross_amount
 * @property float $commission_percent
 * @property float $commission_amount
 * @property string $status
 * @property \Carbon\Carbon|null $cleared_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
final class AffiliateCommission extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

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
        'level' => 'integer',
        'gross_amount' => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'cleared_at' => 'datetime',
    ];

    protected $appends = [
        'transaction_invoice',
        'customer_name',
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

    public function affiliator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Affiliator::class, 'affiliator_id');
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function scopeLevel1($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('level', 1);
    }

    public function scopeLevel2($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('level', 2);
    }

    public function scopePending($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCleared($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_CLEARED);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCleared(): bool
    {
        return $this->status === self::STATUS_CLEARED;
    }

    public function getTransactionInvoiceAttribute(): string
    {
        return $this->transaction?->invoice_number ?? 'Unknown';
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->customer?->name ?? 'Unknown';
    }
}
