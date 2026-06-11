<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string $voucher_id
 * @property string $customer_id
 * @property string $transaction_id
 * @property float $discount_amount
 * @property \Carbon\Carbon $used_at
 */
final class VoucherUsage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'voucher_usage';

    public const INCREMENTING = false;
    public const KEY_TYPE = 'string';

    protected $fillable = [
        'voucher_id',
        'customer_id',
        'transaction_id',
        'discount_amount',
        'used_at',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    public function voucher(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
