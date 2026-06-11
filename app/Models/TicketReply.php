<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string $ticket_id
 * @property string $user_type
 * @property string $user_id
 * @property string $message
 * @property bool $is_internal
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
final class TicketReply extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ticket_replies';

    protected $fillable = [
        'ticket_id',
        'user_type',
        'user_id',
        'message',
        'is_internal',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    public const USER_TYPE_CUSTOMER = 'customer';
    public const USER_TYPE_AFFILIATOR = 'affiliator';
    public const USER_TYPE_ADMIN = 'admin';

    public static function getUserTypes(): array
    {
        return [self::USER_TYPE_CUSTOMER, self::USER_TYPE_AFFILIATOR, self::USER_TYPE_ADMIN];
    }

    public function ticket(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function scopeInternal($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_internal', true);
    }

    public function scopePublic($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_internal', false);
    }

    public function isInternal(): bool
    {
        return $this->is_internal;
    }

    public function isPublic(): bool
    {
        return !$this->is_internal;
    }
}
