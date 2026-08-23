<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiTokenTransaction extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ai_token_transactions';

    public const TYPE_PURCHASE     = 'purchase';
    public const TYPE_SUBSCRIPTION = 'subscription';
    public const TYPE_USAGE        = 'usage';
    public const TYPE_BONUS        = 'bonus';
    public const TYPE_PROMOTION    = 'promotion';
    public const TYPE_REFUND       = 'refund';
    public const TYPE_EXPIRATION   = 'expiration';
    public const TYPE_ADJUSTMENT   = 'adjustment';
    public const TYPE_REVERSAL     = 'reversal';

    protected $fillable = [
        'wallet_id',
        'customer_id',
        'lot_id',
        'type',
        'tokens',
        'balance_before',
        'balance_after',
        'reference_type',
        'reference_id',
        'idempotency_key',
        'description',
        'created_by',
    ];

    protected $casts = [
        'tokens'         => 'integer',
        'balance_before' => 'integer',
        'balance_after'  => 'integer',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(AiWallet::class, 'wallet_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function tokenLot(): BelongsTo
    {
        return $this->belongsTo(AiTokenLot::class, 'lot_id');
    }
}
