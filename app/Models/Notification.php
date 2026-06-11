<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $notifiable_type
 * @property string $notifiable_id
 * @property string $type
 * @property string $channel
 * @property string $subject
 * @property string $message
 * @property array|null $data
 * @property \Carbon\Carbon|null $read_at
 * @property \Carbon\Carbon|null $sent_at
 * @property \Carbon\Carbon|null $failed_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
final class Notification extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'notifications';

    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'type',
        'channel',
        'subject',
        'message',
        'data',
        'read_at',
        'sent_at',
        'failed_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    public const CHANNEL_EMAIL = 'email';
    public const CHANNEL_WHATSAPP = 'whatsapp';
    public const CHANNEL_DATABASE = 'database';

    public static function getChannels(): array
    {
        return [self::CHANNEL_EMAIL, self::CHANNEL_WHATSAPP, self::CHANNEL_DATABASE];
    }

    public function notifiable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function scopeUnread($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeEmail($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('channel', self::CHANNEL_EMAIL);
    }

    public function scopeWhatsapp($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('channel', self::CHANNEL_WHATSAPP);
    }

    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }
}
