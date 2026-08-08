<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class LiveChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_token',
        'customer_name',
        'customer_phone',
        'status',
        'ended_at',
    ];

    protected $casts = [
        'ended_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(LiveChatMessage::class)->orderBy('id', 'asc');
    }
}
