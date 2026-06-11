<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string $name
 * @property string $channel
 * @property string $subject
 * @property string $body
 * @property array|null $variables
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
final class NotificationTemplate extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'notification_templates';

    protected $fillable = [
        'name',
        'channel',
        'subject',
        'body',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public const CHANNEL_EMAIL = 'email';
    public const CHANNEL_WHATSAPP = 'whatsapp';

    public static function getChannels(): array
    {
        return [self::CHANNEL_EMAIL, self::CHANNEL_WHATSAPP];
    }

    public function scopeActive($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeEmail($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('channel', self::CHANNEL_EMAIL);
    }

    public function scopeWhatsapp($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('channel', self::CHANNEL_WHATSAPP);
    }

    public function render(array $data): string
    {
        $body = $this->body;
        
        foreach ($data as $key => $value) {
            $body = str_replace('{{' . $key . '}}', (string) $value, $body);
        }

        return $body;
    }
}
