<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiWallet extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ai_wallets';

    protected $fillable = [
        'customer_id',
        'total_balance',
        'total_purchased',
        'total_used',
        'total_expired',
    ];

    protected $casts = [
        'total_balance'   => 'integer',
        'total_purchased' => 'integer',
        'total_used'      => 'integer',
        'total_expired'   => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function tokenLots(): HasMany
    {
        return $this->hasMany(AiTokenLot::class, 'wallet_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(AiTokenTransaction::class, 'wallet_id');
    }

    /**
     * Get active lots ordered by FEFO (First Expired, First Out).
     */
    public function activeLots()
    {
        return $this->tokenLots()
            ->where('status', AiTokenLot::STATUS_ACTIVE)
            ->where('remaining_tokens', '>', 0)
            ->where('expires_at', '>', now())
            ->orderBy('expires_at', 'asc');
    }
}
