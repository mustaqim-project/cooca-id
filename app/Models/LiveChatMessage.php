<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class LiveChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_chat_id',
        'sender_type',
        'sender_name',
        'message',
    ];

    public function liveChat(): BelongsTo
    {
        return $this->belongsTo(LiveChat::class);
    }
}
