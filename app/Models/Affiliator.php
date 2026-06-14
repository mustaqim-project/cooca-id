<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class Affiliator extends Model
{
    use HasFactory, Notifiable, HasUuids;

    protected $table = 'affiliators';

    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'bank_account',
        'bank_name',
        'parent_affiliator_id',
        'referral_code',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'google_id',
    ];

    protected function casts(): array
    {
        return [
            ...parent::casts(),
            'balance' => 'decimal:2',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Affiliator::class, 'parent_affiliator_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Affiliator::class, 'parent_affiliator_id');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'affiliator_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(AffiliateCommission::class, 'affiliator_id');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(AffiliateWithdrawal::class, 'affiliator_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'affiliator_id');
    }

    public function ticketReplies(): HasMany
    {
        return $this->hasMany(TicketReply::class, 'user_id')
            ->where('user_type', 'affiliator');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id')
            ->where('reviewer_type', 'affiliator');
    }

    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'user', 'user_type', 'user_id');
    }

    public function activityLogs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'causer', 'causer_type', 'causer_id');
    }

    public function scopeDownline($query, ?Affiliator $root = null)
    {
        if ($root === null) {
            return $query;
        }

        return $query->where(function ($q) use ($root) {
            $q->where('parent_affiliator_id', $root->id)
              ->orWhereIn('id', function ($subQ) use ($root) {
                  $subQ->select('id')
                       ->from('affiliators')
                       ->where('parent_affiliator_id', $root->id);
              });
        });
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Customer::class, 'affiliator_id');
    }

    public function downlines(): HasMany
    {
        return $this->hasMany(Affiliator::class, 'parent_affiliator_id');
    }

    public function referralClicks(): int
    {
        return 0;
    }
}
