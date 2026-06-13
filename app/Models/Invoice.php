<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $transaction_id
 * @property string $invoice_number
 * @property string $customer_id
 * @property float $amount
 * @property string $status
 * @property \Carbon\Carbon $issued_at
 * @property \Carbon\Carbon $due_at
 * @property string|null $pdf_path
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
final class Invoice extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'invoices';

    protected $fillable = [
        'transaction_id',
        'invoice_number',
        'customer_id',
        'amount',
        'status',
        'issued_at',
        'due_at',
        'pdf_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'issued_at' => 'datetime',
        'due_at' => 'datetime',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SENT = 'sent';
    public const STATUS_PAID = 'paid';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_CANCELLED = 'cancelled';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_SENT,
            self::STATUS_PAID,
            self::STATUS_OVERDUE,
            self::STATUS_CANCELLED,
        ];
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function scopePaid($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeOverdue($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_OVERDUE)
            ->orWhere(function ($q) {
                $q->where('status', self::STATUS_SENT)
                    ->where('due_at', '<', now());
            });
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE
            || ($this->status === self::STATUS_SENT && $this->due_at->isPast());
    }
}
