<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property string $id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string $content
 * @property string|null $featured_image
 * @property string|null $featured_image_alt
 * @property string $author_id
 * @property string|null $blog_category_id
 * @property string|null $category
 * @property array|null $tags
 * @property bool $is_published
 * @property bool $is_featured
 * @property \Carbon\Carbon|null $published_at
 * @property int $views_count
 * @property int $reading_time_minutes
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $canonical_url
 * @property string|null $og_title
 * @property string|null $og_description
 * @property string|null $og_image
 * @property string|null $og_image_alt
 * @property string|null $focus_keyword
 * @property array|null $schema_markup
 * @property int $seo_score
 * @property int $page_views
 * @property int $unique_visitors
 * @property int $avg_read_duration_seconds
 * @property float $bounce_rate
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
        'featured_image_alt',
        'author_id',
        'blog_category_id',
        'category',
        'tags',
        'is_published',
        'is_featured',
        'published_at',
        'views_count',
        'reading_time_minutes',
        // SEO
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'og_image_alt',
        'focus_keyword',
        'schema_markup',
        'seo_score',
        // Analytics
        'page_views',
        'unique_visitors',
        'avg_read_duration_seconds',
        'bounce_rate',
    ];

    protected $casts = [
        'tags'                    => 'array',
        'schema_markup'           => 'array',
        'is_published'            => 'boolean',
        'is_featured'             => 'boolean',
        'published_at'            => 'datetime',
        'views_count'             => 'integer',
        'reading_time_minutes'    => 'integer',
        'seo_score'               => 'integer',
        'page_views'              => 'integer',
        'unique_visitors'         => 'integer',
        'avg_read_duration_seconds' => 'integer',
        'bounce_rate'             => 'float',
    ];

    // -----------------------------------------------------------------------
    // Relationships
    // -----------------------------------------------------------------------

    public function author(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }

    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    // -----------------------------------------------------------------------
    // Scopes
    // -----------------------------------------------------------------------

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where(function ($q) use ($category) {
            $q->where('category', $category)
              ->orWhereHas('blogCategory', function ($cq) use ($category) {
                  $cq->where('slug', $category)->orWhere('name', $category);
              });
        });
    }

    public function scopeTag($query, string $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    public function incrementViews(): void
    {
        $this->increment('views_count');
        $this->increment('page_views');
    }

    public function isPublished(): bool
    {
        return $this->is_published
            && ($this->published_at === null || $this->published_at->isPast());
    }

    /**
     * Return the featured image URL (storage URL or null).
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (!$this->featured_image) {
            return null;
        }
        return Storage::url($this->featured_image);
    }

    /**
     * Return the OG image URL (storage URL or null).
     */
    public function getOgImageUrlAttribute(): ?string
    {
        if (!$this->og_image) {
            return null;
        }
        return Storage::url($this->og_image);
    }

    /**
     * Calculate reading time dynamically based on word count.
     */
    public function getEstimatedReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->content ?? ''));
        return (int) max(1, ceil($words / 200));
    }

    /**
     * Compute a basic SEO score (0–100) based on filled SEO fields.
     */
    public function computeSeoScore(): int
    {
        $score = 0;
        if (!empty($this->meta_title))       $score += 20;
        if (!empty($this->meta_description)) $score += 20;
        if (!empty($this->focus_keyword))    $score += 15;
        if (!empty($this->og_image))         $score += 15;
        if (!empty($this->og_title))         $score += 10;
        if (!empty($this->canonical_url))    $score += 10;
        if (!empty($this->meta_keywords))    $score += 10;
        return min(100, $score);
    }
}
