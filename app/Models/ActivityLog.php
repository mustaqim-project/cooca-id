<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string|null $user_id
 * @property string|null $user_type
 * @property string|null $log_name
 * @property string $description
 * @property string|null $action
 * @property string|null $module
 * @property string|null $subject_type
 * @property string|null $subject_id
 * @property string|null $causer_type
 * @property string|null $causer_id
 * @property array|null $properties
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property array|null $metadata
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
final class ActivityLog extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'activity_logs';

    public const INCREMENTING = false;
    public const KEY_TYPE = 'string';

    protected $fillable = [
        'user_id',
        'user_type',
        'log_name',
        'description',
        'action',
        'module',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'properties' => 'array',
        'metadata' => 'array',
    ];

    public function subject(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function scopeLogName($query, string $logName): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('log_name', $logName);
    }

    public function scopeForSubject($query, string $subjectType, ?string $subjectId = null): \Illuminate\Database\Eloquent\Builder
    {
        $query->where('subject_type', $subjectType);

        if ($subjectId !== null) {
            $query->where('subject_id', $subjectId);
        }

        return $query;
    }

    public function scopeCausedBy($query, string $causerType, ?string $causerId = null): \Illuminate\Database\Eloquent\Builder
    {
        $query->where('causer_type', $causerType);

        if ($causerId !== null) {
            $query->where('causer_id', $causerId);
        }

        return $query;
    }

    public function getProperty(string $key, mixed $default = null): mixed
    {
        return $this->properties[$key] ?? $default;
    }

    public function getMetadata(string $key, mixed $default = null): mixed
    {
        return $this->metadata[$key] ?? $default;
    }
}
