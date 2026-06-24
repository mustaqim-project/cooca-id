<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Setting extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
        'metadata',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the setting value in the correct type
     */
    public function getTypedValueAttribute(): mixed
    {
        return match ($this->type) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'json', 'array' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    /**
     * Get a setting by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return $setting->typed_value;
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, mixed $value, string $type = 'string'): void
    {
        $serializedValue = match ($type) {
            'json', 'array' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };

        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $serializedValue,
                'type' => $type,
            ]
        );
    }

    /**
     * Get all settings in a group
     */
    public static function getGroup(string $group): array
    {
        return static::where('group', $group)
            ->get()
            ->pluck('typed_value', 'key')
            ->toArray();
    }
}
