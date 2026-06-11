<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string $user_type
 * @property string|null $user_id
 * @property string $action
 * @property string $model_type
 * @property string|null $model_id
 * @property array|null $old_values
 * @property array|null $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string $risk_level
 * @property \Carbon\Carbon $created_at
 */
final class AuditLog extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'audit_logs';

    public const INCREMENTING = false;
    public const KEY_TYPE = 'string';

    protected $fillable = [
        'user_type',
        'user_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'risk_level',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public const USER_TYPE_ADMIN = 'admin';
    public const USER_TYPE_CUSTOMER = 'customer';
    public const USER_TYPE_AFFILIATOR = 'affiliator';
    public const USER_TYPE_SYSTEM = 'system';

    public const RISK_LOW = 'low';
    public const RISK_MEDIUM = 'medium';
    public const RISK_HIGH = 'high';
    public const RISK_CRITICAL = 'critical';

    public static function getUserTypes(): array
    {
        return [self::USER_TYPE_ADMIN, self::USER_TYPE_CUSTOMER, self::USER_TYPE_AFFILIATOR, self::USER_TYPE_SYSTEM];
    }

    public static function getRiskLevels(): array
    {
        return [self::RISK_LOW, self::RISK_MEDIUM, self::RISK_HIGH, self::RISK_CRITICAL];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function model(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function scopeRiskLevel($query, string $riskLevel): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('risk_level', $riskLevel);
    }

    public function scopeHighRisk($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereIn('risk_level', [self::RISK_HIGH, self::RISK_CRITICAL]);
    }

    public function scopeAction($query, string $action): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('action', $action);
    }

    public function isHighRisk(): bool
    {
        return in_array($this->risk_level, [self::RISK_HIGH, self::RISK_CRITICAL], true);
    }
}
