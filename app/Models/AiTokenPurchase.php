<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AiTokenPurchase extends Model
{
    use HasUuids;

    protected $table = 'ai_token_purchases';

    protected $fillable = [
        'customer_id',
        'license_id',
        'ai_token_package_id',
        'transaction_id',
        'tokens_amount',
        'price_paid',
        'status',
        'credited_at',
    ];

    protected $casts = [
        'tokens_amount' => 'integer',
        'price_paid'    => 'decimal:2',
        'credited_at'   => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(AiTokenPackage::class, 'ai_token_package_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
