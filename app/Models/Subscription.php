<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $customer_id
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
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'subscriptions';

    protected $fillable = [
        'customer_id',
        'license_id',
        'subscription_plan_id',
        'status',
        'started_at',
        'expires_at',
        'cancelled_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public const STATUS_TRIAL = 'trial';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_TRIAL,
            self::STATUS_ACTIVE,
            self::STATUS_EXPIRED,
            self::STATUS_CANCELLED,
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
}
