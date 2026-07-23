<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CompanyInfo extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'company_info';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get company info by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $info = static::where('key', $key)->first();
        return $info?->value ?? $default;
    }

    /**
     * Set company info
     */
    public static function set(string $key, string $value, string $type = 'text'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
            ]
        );
    }

    /**
     * Get all info in a group
     */
    public static function getGroup(string $group): array
    {
        return static::where('group', $group)
            ->where('is_active', true)
            ->get()
            ->pluck('value', 'key')
            ->toArray();
    }
}

