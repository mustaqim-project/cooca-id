<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $subscription_id
 * @property string|null $from_status
 * @property string $to_status
 * @property string|null $reason
 * @property string|null $actor_id
 * @property string|null $actor_type
 * @property float|null $amount
 * @property \Carbon\Carbon $created_at
 */
final class SubscriptionStatusHistory extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'subscription_status_histories';

    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = null;

    protected $fillable = [
        'subscription_id',
        'from_status',
        'to_status',
        'reason',
        'actor_id',
        'actor_type',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}
