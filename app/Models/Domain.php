<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Domain extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'domains';

    protected $fillable = [
        'customer_id',
        'erp_request_id',
        'domain',
        'type',
        'status',
        'dns_notes',
        'ssl_notes',
        'setup_notes',
        'verified_at',
        'activated_at',
        'failed_at',
        'failure_reason',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'activated_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_VERIFICATION_REQUIRED = 'verification_required';
    public const STATUS_WAITING_SETUP = 'waiting_setup';
    public const STATUS_IN_SETUP = 'in_setup';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_FAILED = 'failed';

    // Type constants
    public const TYPE_SUBDOMAIN = 'subdomain';
    public const TYPE_CUSTOM_DOMAIN = 'custom_domain';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_VERIFICATION_REQUIRED,
            self::STATUS_WAITING_SETUP,
            self::STATUS_IN_SETUP,
            self::STATUS_ACTIVE,
            self::STATUS_FAILED,
        ];
    }

    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_VERIFICATION_REQUIRED => 'Verification Required',
            self::STATUS_WAITING_SETUP => 'Waiting Setup',
            self::STATUS_IN_SETUP => 'In Setup',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_FAILED => 'Failed',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function erpRequest(): BelongsTo
    {
        return $this->belongsTo(ErpRequest::class, 'erp_request_id');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function markAsVerified(): void
    {
        $this->update([
            'status' => self::STATUS_WAITING_SETUP,
            'verified_at' => now(),
        ]);
    }

    public function markInSetup(): void
    {
        $this->update(['status' => self::STATUS_IN_SETUP]);
    }

    public function markAsActive(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'activated_at' => now(),
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
}
