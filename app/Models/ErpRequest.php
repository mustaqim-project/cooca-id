<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class ErpRequest extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'erp_requests';

    protected $fillable = [
        'user_id',
        'product_id',
        'affiliate_id',
        'requested_domain',
        'requested_subdomain',
        'status',
        'notes',
        'admin_notes',
        'approved_by',
        'approved_at',
        'setup_started_at',
        'testing_at',
        'activated_at',
        'rejected_at',
        'trial_starts_at',
        'trial_ends_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'setup_started_at' => 'datetime',
        'testing_at' => 'datetime',
        'activated_at' => 'datetime',
        'rejected_at' => 'datetime',
        'trial_starts_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    // Status constants
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_WAITING_APPROVAL = 'waiting_approval';
    public const STATUS_WAITING_SETUP = 'waiting_setup';
    public const STATUS_IN_SETUP = 'in_setup';
    public const STATUS_DOMAIN_SETUP = 'domain_setup';
    public const STATUS_TESTING = 'testing';
    public const STATUS_ACTIVE_TRIAL = 'active_trial';
    public const STATUS_TRIAL_EXPIRED = 'trial_expired';
    public const STATUS_REJECTED = 'rejected';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_SUBMITTED,
            self::STATUS_WAITING_APPROVAL,
            self::STATUS_WAITING_SETUP,
            self::STATUS_IN_SETUP,
            self::STATUS_DOMAIN_SETUP,
            self::STATUS_TESTING,
            self::STATUS_ACTIVE_TRIAL,
            self::STATUS_TRIAL_EXPIRED,
            self::STATUS_REJECTED,
        ];
    }

    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_WAITING_APPROVAL => 'Waiting Approval',
            self::STATUS_WAITING_SETUP => 'Waiting Setup',
            self::STATUS_IN_SETUP => 'In Setup',
            self::STATUS_DOMAIN_SETUP => 'Domain Setup',
            self::STATUS_TESTING => 'Testing',
            self::STATUS_ACTIVE_TRIAL => 'Active Trial',
            self::STATUS_TRIAL_EXPIRED => 'Trial Expired',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function affiliator(): BelongsTo
    {
        return $this->belongsTo(Affiliator::class, 'affiliate_id');
    }

    public function adminApproved(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class, 'erp_request_id');
    }

    public function license(): HasOne
    {
        return $this->hasOne(License::class, 'erp_request_id');
    }

    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isWaitingApproval(): bool
    {
        return $this->status === self::STATUS_WAITING_APPROVAL;
    }

    public function isActiveTrial(): bool
    {
        return $this->status === self::STATUS_ACTIVE_TRIAL;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isTrialExpired(): bool
    {
        return $this->status === self::STATUS_TRIAL_EXPIRED 
            || ($this->trial_ends_at && $this->trial_ends_at->isPast());
    }

    public function markAsWaitingApproval(): void
    {
        $this->update(['status' => self::STATUS_WAITING_APPROVAL]);
    }

    public function approve(string $adminId): void
    {
        $this->update([
            'status' => self::STATUS_WAITING_SETUP,
            'approved_by' => $adminId,
            'approved_at' => now(),
        ]);
    }

    public function reject(): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejected_at' => now(),
        ]);
    }

    public function markInSetup(): void
    {
        $this->update([
            'status' => self::STATUS_IN_SETUP,
            'setup_started_at' => now(),
        ]);
    }

    public function markDomainSetup(): void
    {
        $this->update(['status' => self::STATUS_DOMAIN_SETUP]);
    }

    public function markTesting(): void
    {
        $this->update([
            'status' => self::STATUS_TESTING,
            'testing_at' => now(),
        ]);
    }

    public function activateTrial(\DateTimeInterface $startsAt, \DateTimeInterface $endsAt): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE_TRIAL,
            'activated_at' => now(),
            'trial_starts_at' => $startsAt,
            'trial_ends_at' => $endsAt,
        ]);
    }
}

