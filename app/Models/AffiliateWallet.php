<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class AffiliateWallet extends Model
{
    use HasUuids;

    protected $table = 'affiliate_wallets';

    protected $fillable = [
        'affiliator_id',
        'balance',
        'pending_balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
    ];

    public function affiliator(): BelongsTo
    {
        return $this->belongsTo(Affiliator::class, 'affiliator_id');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(AffiliateWithdrawal::class, 'affiliator_id', 'affiliator_id');
    }

    public function getAvailableBalanceAttribute(): string
    {
        return number_format((float) $this->balance, 2);
    }

    public function getTotalBalanceAttribute(): string
    {
        return number_format((float) $this->balance + (float) $this->pending_balance, 2);
    }
}
