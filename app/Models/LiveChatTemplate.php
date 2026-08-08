<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class LiveChatTemplate extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'live_chat_templates';

    protected $fillable = [
        'title',
        'shortcut',
        'content',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            ...parent::casts(),
            'is_active'  => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }
}
