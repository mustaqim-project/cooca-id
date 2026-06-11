<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getActiveProducts(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->with(['category', 'subscriptionPlans'])
            ->orderBy('sort_order')
            ->get();
    }

    public function getFeaturedProducts(int $limit = 6): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->where('is_featured', true)
            ->with(['category', 'subscriptionPlans'])
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->model
            ->with(['category', 'subscriptionPlans'])
            ->where('slug', $slug)
            ->first();
    }

    public function getProductsByCategory(string $categorySlug): Collection
    {
        return $this->model
            ->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            })
            ->where('is_active', true)
            ->with(['category', 'subscriptionPlans'])
            ->orderBy('sort_order')
            ->get();
    }

    public function clearCache(): void
    {
        Cache::tags(['products'])->flush();
    }
}
