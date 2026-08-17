<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class AiApiKey extends Model
{
    use HasUuids;

    protected $table = 'ai_api_keys';

    protected $fillable = [
        'license_id',
        'customer_id',
        'domain_id',
        'name',
        'key_prefix',
        'key_hash',
        'secret_key',
        'status',
        'last_used_at',
        'revoked_at',
    ];

    protected $casts = [
        'secret_key' => 'encrypted',
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    protected $hidden = [
        'key_hash',
    ];

    public function getPlainKeyAttribute(): ?string
    {
        return $this->secret_key ?? null;
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function domain(): BelongsTo
    {
        return $this->belongsTo(Domain::class);
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(AiUsageLog::class);
    }
}
