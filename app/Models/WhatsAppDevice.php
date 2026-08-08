<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WhatsAppDevice extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_devices';

    protected $fillable = [
        'uuid',
        'owner_type',
        'owner_id',
        'name',
        'session_id',
        'api_key',
        'phone_number',
        'status',
        'qr_code',
        'webhook_url',
    ];


    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Generate unique session ID for the device owner.
     */
    public static function generateSessionId(string $ownerType, int|string $ownerId): string
    {
        $prefix = strtolower(substr($ownerType, 0, 4));
        $cleanOwnerId = preg_replace('/[^a-zA-Z0-9]/', '', (string)$ownerId);
        return 'wa_' . $prefix . '_' . substr($cleanOwnerId, 0, 8) . '_' . Str::random(8);
    }

    /**
     * Generate secret API key for external integrations.
     */
    public static function generateApiKey(): string
    {
        return 'wapi_sec_' . Str::random(40);
    }

    /**
     * Polymorphic owner relationship (Admin or Customer).
     */
    public function owner()
    {
        return $this->morphTo(null, 'owner_type', 'owner_id');
    }
}
