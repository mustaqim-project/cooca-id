<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'payments';

    protected $fillable = [
        'transaction_id',
        'invoice_id',
        'payment_method',
        'payment_gateway',
        'amount',
        'status',
        'gateway_transaction_id',
        'gateway_response',
        'paid_at',
        'failed_at',
        'refunded_at',
        'failure_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'failed_at' => 'datetime',
        'refunded_at' => 'datetime',
        'gateway_response' => 'array',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isRefunded(): bool
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    public function markAsSuccess(\DateTimeInterface $paidAt): void
    {
        $this->update([
            'status' => self::STATUS_SUCCESS,
            'paid_at' => $paidAt,
        ]);
    }

    public function markAsFailed(string $reason): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'failed_at' => now(),
            'failure_reason' => $reason,
        ]);
    }

    public function markAsRefunded(): void
    {
        $this->update([
            'status' => self::STATUS_REFUNDED,
            'refunded_at' => now(),
        ]);
    }
}
