<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $customer_id
 * @property string $erp_product_id
 * @property string $subscription_plan_id
 * @property string $subdomain
 * @property string|null $affiliator_id
 * @property string $status
 * @property string|null $rejection_reason
 * @property \Carbon\Carbon|null $submitted_at
 * @property \Carbon\Carbon|null $approved_at
 * @property \Carbon\Carbon|null $started_at
 * @property \Carbon\Carbon|null $expires_at
 * @property \Carbon\Carbon|null $converted_at
 * @property string|null $subscription_id
 * @property array|null $provisioning_config
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
final class Trial extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'trials';

    protected $fillable = [
        'customer_id',
        'erp_product_id',
        'subscription_plan_id',
        'subdomain',
        'affiliator_id',
        'status',
        'rejection_reason',
        'submitted_at',
        'approved_at',
        'started_at',
        'expires_at',
        'converted_at',
        'subscription_id',
        'provisioning_config',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'converted_at' => 'datetime',
        'provisioning_config' => 'array',
    ];

    // Status constants matching migration
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_WAITING_APPROVAL = 'waiting_approval';
    public const STATUS_WAITING_PROVISIONING = 'waiting_provisioning';
    public const STATUS_PROVISIONING = 'provisioning';
    public const STATUS_DOMAIN_SETUP = 'domain_setup';
    public const STATUS_TESTING = 'testing';
    public const STATUS_ACTIVE_TRIAL = 'active_trial';
    public const STATUS_CONVERTED_TO_SUBSCRIPTION = 'converted_to_subscription';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_FAILED = 'failed';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_SUBMITTED,
            self::STATUS_WAITING_APPROVAL,
            self::STATUS_WAITING_PROVISIONING,
            self::STATUS_PROVISIONING,
            self::STATUS_DOMAIN_SETUP,
            self::STATUS_TESTING,
            self::STATUS_ACTIVE_TRIAL,
            self::STATUS_CONVERTED_TO_SUBSCRIPTION,
            self::STATUS_REJECTED,
            self::STATUS_EXPIRED,
            self::STATUS_FAILED,
        ];
    }

    public static function getActiveStatuses(): array
    {
        return [
            self::STATUS_ACTIVE_TRIAL,
        ];
    }

    public static function getPendingStatuses(): array
    {
        return [
            self::STATUS_SUBMITTED,
            self::STATUS_WAITING_APPROVAL,
            self::STATUS_WAITING_PROVISIONING,
            self::STATUS_PROVISIONING,
            self::STATUS_DOMAIN_SETUP,
            self::STATUS_TESTING,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function erpProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'erp_product_id');
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function affiliator(): BelongsTo
    {
        return $this->belongsTo(Affiliator::class, 'affiliator_id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(TrialStatusHistory::class, 'trial_id');
    }

    /**
     * Check if trial is in active state
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE_TRIAL
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Check if trial is expired
     */
    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED
            || ($this->expires_at && $this->expires_at->isPast() && $this->status !== self::STATUS_CONVERTED_TO_SUBSCRIPTION);
    }

    /**
     * Check if trial is pending approval
     */
    public function isPendingApproval(): bool
    {
        return $this->status === self::STATUS_WAITING_APPROVAL;
    }

    /**
     * Check if trial is converted to subscription
     */
    public function isConverted(): bool
    {
        return $this->status === self::STATUS_CONVERTED_TO_SUBSCRIPTION;
    }

    /**
     * Check if trial is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Get days remaining until expiration
     */
    public function daysRemaining(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }

        return max(0, now()->diffInDays($this->expires_at, false));
    }

    /**
     * Scope for active trials
     */
    public function scopeActive($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_ACTIVE_TRIAL)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope for expiring trials within given days
     */
    public function scopeExpiringWithin($query, int $days): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_ACTIVE_TRIAL)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }

    /**
     * Scope for pending trials awaiting approval
     */
    public function scopePendingApproval($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_WAITING_APPROVAL);
    }

    /**
     * Record status change in history
     */
    public function recordStatusChange(
        string $toStatus,
        ?string $reason = null,
        ?string $actorId = null,
        ?string $actorType = null
    ): TrialStatusHistory {
        return TrialStatusHistory::create([
            'trial_id' => $this->id,
            'from_status' => $this->status,
            'to_status' => $toStatus,
            'reason' => $reason,
            'actor_id' => $actorId,
            'actor_type' => $actorType,
        ]);
    }
}
