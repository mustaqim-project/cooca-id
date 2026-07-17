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
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

final class Admin extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, HasUuids;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            ...parent::casts(),
            'permissions' => 'array',
        ];
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class, 'revoked_by');
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class, 'created_by');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'created_by');
    }

    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    public function emailCampaigns(): HasMany
    {
        return $this->hasMany(EmailCampaign::class, 'created_by');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'admin_id');
    }

    public function ticketReplies(): HasMany
    {
        return $this->hasMany(TicketReply::class, 'user_id')
            ->where('user_type', 'admin');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(AffiliateWithdrawal::class, 'approved_by');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'approved_by');
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
