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
        'phone',
        'phone_verified_at',
        'wa_otp_code',
        'wa_otp_expires_at',
        'password',
        'business_name',
        'logo_path',
        'domain',
        'affiliator_id',
        'google_id',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'wa_otp_expires_at' => 'datetime',
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

    public function sendEmailVerificationNotification(): void
    {
        $notification = new \Illuminate\Auth\Notifications\VerifyEmail();
        $notification->createUrlUsing(function ($notifiable) {
            return \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'customer.verification.verify',
                now()->addMinutes((int) config('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });
        $this->notify($notification);
    }

    public function getLogoUrlAttribute(): ?string
    {
        $logo = $this->logo_path ?? $this->companyProfile?->logo_path;
        if (!$logo) return null;
        // If already a full URL, return as-is
        if (str_starts_with($logo, 'http')) return $logo;
        // Path is already absolute-like (/uploads/logos/...) or relative, serve from public
        return asset(ltrim($logo, '/'));
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

    public function getReferredByIdAttribute(): ?string
    {
        return $this->affiliator_id;
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

    public function trials(): HasMany
    {
        return $this->hasMany(Trial::class, 'customer_id');
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

    public function aiWallet(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AiWallet::class, 'customer_id');
    }

    public function aiTokenLots(): HasMany
    {
        return $this->hasMany(AiTokenLot::class, 'customer_id');
    }

    public function aiTokenTransactions(): HasMany
    {
        return $this->hasMany(AiTokenTransaction::class, 'customer_id');
    }

    public function aiUsageLogs(): HasMany
    {
        return $this->hasMany(AiUsageLog::class, 'customer_id');
    }

    public function getOrCreateAiWallet(): AiWallet
    {
        return $this->aiWallet()->firstOrCreate([
            'customer_id' => $this->getKey(),
        ], [
            'total_balance'   => 0,
            'total_purchased' => 0,
            'total_used'      => 0,
            'total_expired'   => 0,
        ]);
    }

    public function getAvailableAiTokens(): int
    {
        return (int) $this->aiTokenLots()
            ->where('status', AiTokenLot::STATUS_ACTIVE)
            ->where('remaining_tokens', '>', 0)
            ->where('expires_at', '>', now())
            ->sum('remaining_tokens');
    }

    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'user', 'user_type', 'user_id');
    }

    public function activityLogs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'causer', 'causer_type', 'causer_id');
    }

    /**
     * Check if customer has completed their company profile
     */
    public function isCompanyProfileComplete(): bool
    {
        $profile = $this->companyProfile;
        if (!$profile) {
            return false;
        }

        return !empty($profile->company_name) && 
               !empty($profile->phone) && 
               !empty($profile->address);
    }
}
