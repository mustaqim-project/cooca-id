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

    // ponytail: product_type as string enum; upgrade to DB enum or separate table if types become dynamic
    public const TYPES = [
        'saas'        => 'SaaS',
        'lifetime'    => 'Lifetime Software',
        'license'     => 'Licensing',
        'subscription' => 'Subscription',
        'addon'       => 'Add-on',
        'bundle'      => 'Bundle',
        'custom_dev'  => 'Custom Development',
        'maintenance' => 'Maintenance',
        'project'     => 'Project-Based',
    ];

    public const LICENSE_TYPES = [
        'perpetual'    => 'Perpetual',
        'annual'       => 'Annual',
        'monthly'      => 'Monthly',
        'domain_based' => 'Domain-Based',
    ];

    protected $fillable = [
        'category_id',
        'product_type',
        'license_type',
        'name',
        'slug',
        'icon',
        'description',
        'short_description',
        'base_price',
        'setup_fee',
        'maintenance_fee',
        'features',
        'specifications',
        'requirements',
        'demo_url',
        'version',
        'max_domains',
        'thumbnail',
        'screenshots',
        'is_active',
        'is_featured',
        'is_bundleable',
        'sort_order',
        'views',
    ];

    protected function casts(): array
    {
        return [
            ...parent::casts(),
            'base_price' => 'decimal:2',
            'setup_fee' => 'decimal:2',
            'maintenance_fee' => 'decimal:2',
            'features' => 'array',
            'specifications' => 'array',
            'screenshots' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_bundleable' => 'boolean',
        ];
    }

    public function getProductTypeLabelAttribute(): string
    {
        return self::TYPES[$this->product_type] ?? $this->product_type;
    }

    public function getLicenseTypeLabelAttribute(): ?string
    {
        return $this->license_type ? (self::LICENSE_TYPES[$this->license_type] ?? $this->license_type) : null;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) return null;
        if (str_starts_with($this->thumbnail, 'http')) return $this->thumbnail;
        if (str_starts_with($this->thumbnail, '/')) return $this->thumbnail;
        
        return '/' . ltrim($this->thumbnail, '/');
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
