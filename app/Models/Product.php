<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

final class Product extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'icon',
        'description',
        'short_description',
        'base_price',
        'features',
        'specifications',
        'demo_url',
        'thumbnail',
        'screenshots',
        'is_active',
        'is_featured',
        'sort_order',
        'views',
    ];

    protected function casts(): array
    {
        return [
            ...parent::casts(),
            'base_price' => 'decimal:2',
            'features' => 'array',
            'specifications' => 'array',
            'screenshots' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function subscriptionPlans(): HasMany
    {
        return $this->hasMany(SubscriptionPlan::class, 'product_id');
    }

    /** Alias for subscriptionPlans() */
    public function plans(): HasMany
    {
        return $this->subscriptionPlans();
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class, 'product_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
