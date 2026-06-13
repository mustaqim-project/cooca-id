<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class LicenseActivation extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'license_activations';

    protected $fillable = [
        'license_id',
        'domain',
        'ip_address',
        'user_agent',
        'activated_at',
        'deactivated_at',
        'status',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'deactivated_at' => 'datetime',
    ];

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_SUSPENDED = 'suspended';

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class, 'license_id');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function deactivate(): void
    {
        $this->update([
            'status' => self::STATUS_INACTIVE,
            'deactivated_at' => now(),
        ]);
    }

    public function suspend(): void
    {
        $this->update(['status' => self::STATUS_SUSPENDED]);
    }
}
