<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ApiIntegration extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'name',
        'label',
        'category',
        'is_active',
        'credentials',
        'config',
        'description',
        'last_used_at',
        'tested_at',
        'test_status',
        'test_message',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credentials' => 'array',
        'config' => 'array',
        'last_used_at' => 'datetime',
        'tested_at' => 'datetime',
        'test_status' => 'boolean',
    ];

    /**
     * Get available categories
     */
    public static function getCategories(): array
    {
        return [
            'messaging' => 'Messaging (WhatsApp, SMS)',
            'email' => 'Email (SMTP, Mail Service)',
            'authentication' => 'Authentication (Google, Facebook)',
            'payment' => 'Payment Gateway (Midtrans, Xendit)',
            'storage' => 'Cloud Storage (AWS S3, Google Cloud)',
            'analytics' => 'Analytics & Tracking',
        ];
    }

    /**
     * Get integration by name
     */
    public static function getByName(string $name): ?self
    {
        return static::where('name', $name)->first();
    }

    /**
     * Check if integration is active and configured
     */
    public function isConfigured(): bool
    {
        return $this->is_active && !empty($this->credentials);
    }

    /**
     * Get credential value by key
     */
    public function getCredential(string $key, mixed $default = null): mixed
    {
        return $this->credentials[$key] ?? $default;
    }

    /**
     * Update credentials
     */
    public function updateCredentials(array $credentials): void
    {
        $this->update([
            'credentials' => array_merge($this->credentials ?? [], $credentials),
        ]);
    }

    /**
     * Mark as tested
     */
    public function markTested(bool $status, string $message = null): void
    {
        $this->update([
            'tested_at' => now(),
            'test_status' => $status,
            'test_message' => $message,
        ]);
    }

    /**
     * Mark as used
     */
    public function markUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }
}
