<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class LicenseLog extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'license_logs';

    protected $fillable = [
        'license_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public const ACTION_GENERATED = 'generated';
    public const ACTION_ACTIVATED = 'activated';
    public const ACTION_VALIDATED = 'validated';
    public const ACTION_SUSPENDED = 'suspended';
    public const ACTION_REVOKED = 'revoked';
    public const ACTION_EXPIRED = 'expired';

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class, 'license_id');
    }

    public static function log(string $licenseId, string $action, ?string $description = null, ?array $metadata = null, ?string $ipAddress = null, ?string $userAgent = null): self
    {
        return self::create([
            'license_id' => $licenseId,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
}
