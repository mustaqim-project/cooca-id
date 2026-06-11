<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $id
 * @property string $name
 * @property string $subject
 * @property string $content
 * @property string $recipient_type
 * @property array|null $segment_criteria
 * @property int $total_recipients
 * @property int $sent_count
 * @property int $opened_count
 * @property int $clicked_count
 * @property string $status
 * @property \Carbon\Carbon|null $scheduled_at
 * @property \Carbon\Carbon|null $sent_at
 * @property string $created_by
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
final class EmailCampaign extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'email_campaigns';

    protected $fillable = [
        'name',
        'subject',
        'content',
        'recipient_type',
        'segment_criteria',
        'total_recipients',
        'sent_count',
        'opened_count',
        'clicked_count',
        'status',
        'scheduled_at',
        'sent_at',
        'created_by',
    ];

    protected $casts = [
        'segment_criteria' => 'array',
        'total_recipients' => 'integer',
        'sent_count' => 'integer',
        'opened_count' => 'integer',
        'clicked_count' => 'integer',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public const RECIPIENT_CUSTOMERS = 'customers';
    public const RECIPIENT_AFFILIATORS = 'affiliators';
    public const RECIPIENT_ALL = 'all';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_SENDING = 'sending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    public static function getRecipientTypes(): array
    {
        return [self::RECIPIENT_CUSTOMERS, self::RECIPIENT_AFFILIATORS, self::RECIPIENT_ALL];
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_SCHEDULED,
            self::STATUS_SENDING,
            self::STATUS_COMPLETED,
            self::STATUS_FAILED,
        ];
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function scopeScheduled($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    public function scopeCompleted($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}
