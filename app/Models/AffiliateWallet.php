<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AffiliateWallet extends Model
{
    protected $table = 'affiliate_wallets';

    protected $fillable = [
        'user_id',
        'balance',
        'pending_balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(AffiliateWithdrawal::class, 'affiliate_id', 'user_id');
    }

    public function getAvailableBalanceAttribute(): string
    {
        return number_format($this->balance, 2);
    }

    public function getTotalBalanceAttribute(): string
    {
        return number_format($this->balance + $this->pending_balance, 2);
    }
}
