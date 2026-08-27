<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $user_id
 * @property string $product_id
 * @property string $subscription_plan_id
 * @property string $license_code
 * @property string $token_code
 * @property string $domain
 * @property string $status
 * @property \Carbon\Carbon|null $activated_at
 * @property \Carbon\Carbon|null $expires_at
 * @property \Carbon\Carbon|null $revoked_at
 * @property string|null $revoked_by
 * @property string|null $revocation_reason
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
final class License extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'licenses';

    protected $fillable = [
        'customer_id',
        'user_id',
        'product_id',
        'subscription_plan_id',
        'erp_request_id',
        'subscription_id',
        'domain_id',
        'license_code',
        'token_code',
        'domain',
        'status',
        'is_trial',
        'activated_at',
        'starts_at',
        'expires_at',
        'revoked_at',
        'revoked_by',
        'revocation_reason',
        'revocation_category',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
        'starts_at' => 'datetime',
        'is_trial' => 'boolean',
    ];

    protected $hidden = [
        'token_code',
    ];

    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_REVOKED = 'revoked';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_INACTIVE,
            self::STATUS_ACTIVE,
            self::STATUS_SUSPENDED,
            self::STATUS_EXPIRED,
            self::STATUS_REVOKED,
        ];
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function subscriptionPlan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function erpRequest(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ErpRequest::class, 'erp_request_id');
    }

    public function subscription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function domain(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function domainRecord(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function adminRevoked(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'revoked_by');
    }

    public function appeals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LicenseAppeal::class);
    }

    public function latestAppeal(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(LicenseAppeal::class)->latestOfMany();
    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class, 'license_id');
    }

    public function usageCycles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AiUsageCycle::class, 'license_id');
    }

    public function scopeActive($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeExpired($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopeRevoked($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_REVOKED);
    }

    public function scopeForDomain($query, string $domain): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('domain', $domain);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED
            || ($this->expires_at !== null && $this->expires_at->isPast());
    }

    public function isRevoked(): bool
    {
        return $this->status === self::STATUS_REVOKED;
    }

    public function activate(string $domain): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'domain' => $domain,
            'activated_at' => now(),
        ]);
    }

    public function revoke(string $adminId, string $reason): void
    {
        $this->update([
            'status' => self::STATUS_REVOKED,
            'revoked_at' => now(),
            'revoked_by' => $adminId,
            'revocation_reason' => $reason,
        ]);
    }
}

