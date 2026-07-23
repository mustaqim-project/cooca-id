<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $reviewable_type
 * @property string $reviewable_id
 * @property string $reviewer_type
 * @property string $reviewer_id
 * @property int $rating
 * @property string|null $title
 * @property string|null $comment
 * @property bool $is_approved
 * @property string|null $approved_by
 * @property \Carbon\Carbon|null $approved_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 */
final class Review extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'reviews';

    protected $fillable = [
        'reviewable_type',
        'reviewable_id',
        'reviewer_type',
        'reviewer_id',
        'rating',
        'title',
        'comment',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public const REVIEWER_TYPE_CUSTOMER = 'customer';
    public const REVIEWER_TYPE_AFFILIATOR = 'affiliator';

    public static function getReviewerTypes(): array
    {
        return [self::REVIEWER_TYPE_CUSTOMER, self::REVIEWER_TYPE_AFFILIATOR];
    }

    public function reviewable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function reviewer(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function approver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function scopeApproved($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('is_approved', false);
    }

    public function scopeRating($query, int $rating): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('rating', $rating);
    }

    public function scopeMinRating($query, int $minRating): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('rating', '>=', $minRating);
    }

    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    public function approve(string $adminId): void
    {
        $this->update([
            'is_approved' => true,
            'approved_by' => $adminId,
            'approved_at' => now(),
        ]);
    }

    // Compatibility Accessors for views
    public function getProductAttribute()
    {
        return $this->reviewable_type === Product::class ? $this->reviewable : null;
    }

    public function getCustomerAttribute()
    {
        return $this->reviewer_type === 'customer' ? $this->reviewer : null;
    }

    public function getAffiliatorAttribute()
    {
        return $this->reviewer_type === 'affiliator' ? $this->reviewer : null;
    }

    public function getStatusAttribute()
    {
        return $this->is_approved ? 'approved' : 'pending';
    }
}

