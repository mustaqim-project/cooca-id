<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class AiTokenPackage extends Model
{
    use HasUuids;

    protected $table = 'ai_token_packages';

    protected $fillable = [
        'name',
        'slug',
        'token_amount',
        'price',
        'description',
        'badge',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'token_amount' => 'integer',
        'price'        => 'decimal:2',
        'is_active'    => 'boolean',
        'sort_order'   => 'integer',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(AiTokenPurchase::class, 'ai_token_package_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }
}
