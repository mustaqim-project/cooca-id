<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Contracts\Auth\MustVerifyEmail;

final class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, HasUuids;
    protected $table = 'customers';
    protected $guard_name = 'customer';

    protected $fillable = [
        'name',
        'email',
        'password',
        'business_name',
        'domain',
        'affiliator_id',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'google_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Customer $customer): void {
            if (blank($customer->password)) {
                $customer->password = Hash::make(Str::random(32));
            }
        });
    }

    public function companyProfile(): HasOne
    {
        return $this->hasOne(CompanyProfile::class);
    }

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function erpRequests(): HasMany
    {
        return $this->hasMany(ErpRequest::class);
    }

    public function affiliator(): BelongsTo
    {
        return $this->belongsTo(Affiliator::class, 'affiliator_id');
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class, 'customer_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'customer_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'customer_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'customer_id');
    }

    public function ticketReplies(): HasMany
    {
        return $this->hasMany(TicketReply::class, 'user_id')
            ->where('user_type', 'customer');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id')
            ->where('reviewer_type', 'customer');
    }

    public function voucherUsage(): HasMany
    {
        return $this->hasMany(VoucherUsage::class, 'customer_id');
    }

    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'user', 'user_type', 'user_id');
    }

    public function activityLogs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'causer', 'causer_type', 'causer_id');
    }
}
