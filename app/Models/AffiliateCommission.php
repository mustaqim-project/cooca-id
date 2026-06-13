<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateCommission extends Model
{
    protected $table = 'affiliate_commissions';

    protected $fillable = [
        'affiliate_id',
        'order_id',
        'customer_id',
        'level',
        'gross_amount',
        'commission_rate',
        'commission_amount',
        'status',
        'settled_at',
        'calculated_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'commission_rate' => 'decimal:4',
        'commission_amount' => 'decimal:2',
        'settled_at' => 'datetime',
        'calculated_at' => 'datetime',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affiliate_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->commission_amount, 0, ',', '.');
    }

    public function getFormattedGrossAttribute(): string
    {
        return number_format($this->gross_amount, 0, ',', '.');
    }

    public function getPercentageAttribute(): string
    {
        return number_format($this->commission_rate * 100, 1) . '%';
    }
}
