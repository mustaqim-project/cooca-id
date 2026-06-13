<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class MidtransTransaction extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'midtrans_transactions';

    protected $fillable = [
        'transaction_id',
        'order_id',
        'gross_amount',
        'currency',
        'payment_type',
        'transaction_status',
        'fraud_status',
        'status_code',
        'raw_response',
        'transaction_time',
        'settlement_time',
        'expire_time',
    ];

    protected $casts = [
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'expire_time' => 'datetime',
        'raw_response' => 'array',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function isSuccessful(): bool
    {
        return in_array($this->transaction_status, ['capture', 'settlement']);
    }

    public function isPending(): bool
    {
        return $this->transaction_status === 'pending';
    }

    public function isFailed(): bool
    {
        return in_array($this->transaction_status, ['deny', 'cancel', 'expire']);
    }

    public function updateFromMidtransResponse(array $response): void
    {
        $this->update([
            'transaction_status' => $response['transaction_status'] ?? null,
            'fraud_status' => $response['fraud_status'] ?? null,
            'status_code' => $response['status_code'] ?? null,
            'payment_type' => $response['payment_type'] ?? null,
            'gross_amount' => $response['gross_amount'] ?? $this->gross_amount,
            'currency' => $response['currency'] ?? $this->currency,
            'raw_response' => $response,
            'transaction_time' => isset($response['transaction_time']) ? date('Y-m-d H:i:s', strtotime($response['transaction_time'])) : null,
            'settlement_time' => isset($response['settlement_time']) ? date('Y-m-d H:i:s', strtotime($response['settlement_time'])) : null,
            'expire_time' => isset($response['expiry_time']) ? date('Y-m-d H:i:s', strtotime($response['expiry_time'])) : null,
        ]);
    }
}
