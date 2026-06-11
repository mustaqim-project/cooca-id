<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property string $content
 * @property string|null $featured_image
 * @property string $author_id
 * @property string|null $category
 * @property array|null $tags
 * @property bool $is_published
 * @property \Carbon\Carbon|null $published_at
 * @property int $views_count
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
final class BlogPost extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'blog_posts';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'author_id',
        'category',
        'tags',
        'is_published',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }

    public function scopePublished($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeCategory($query, string $category): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('category', $category);
    }

    public function scopeTag($query, string $tag): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereJsonContains('tags', $tag);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    public function isPublished(): bool
    {
        return $this->is_published 
            && ($this->published_at === null || $this->published_at->isPast());
    }
}
