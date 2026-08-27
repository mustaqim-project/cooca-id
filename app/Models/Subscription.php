<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $user_id
 * @property string $license_id
 * @property string $subscription_plan_id
 * @property string $status
 * @property \Carbon\Carbon|null $started_at
 * @property \Carbon\Carbon|null $expires_at
 * @property \Carbon\Carbon|null $cancelled_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
final class Subscription extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'subscriptions';

    protected $fillable = [
        'customer_id',
        'license_id',
        'subscription_plan_id',
        'status',
        'started_at',
        'expires_at',
        'cancelled_at',
        'auto_renew',
        'grace_period_ends_at',
        'suspended_at',
        'cancellation_reason',
        'previous_plan_id',
        'prorated_amount',
        'cycle_number',
        'renewal_count',
        'last_renewed_at',
        'trial_id',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'auto_renew' => 'boolean',
        'grace_period_ends_at' => 'datetime',
        'suspended_at' => 'datetime',
        'prorated_amount' => 'decimal:2',
        'cycle_number' => 'integer',
        'renewal_count' => 'integer',
        'last_renewed_at' => 'datetime',
    ];

    public const STATUS_TRIAL = 'trial';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_SUSPENDED = 'suspended';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_TRIAL,
            self::STATUS_ACTIVE,
            self::STATUS_EXPIRED,
            self::STATUS_CANCELLED,
            self::STATUS_SUSPENDED,
        ];
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function license(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(License::class, 'license_id');
    }

    public function subscriptionPlan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class, 'subscription_id');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function scopeActive($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function getEndsAtAttribute(): ?\Carbon\Carbon
    {
        return $this->expires_at;
    }

    public function setEndsAtAttribute($value): void
    {
        $this->attributes['expires_at'] = $value;
    }
}
