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

    public function getByCategory(string $categoryId): Collection
    {
        return $this->model
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->with(['category', 'subscriptionPlans'])
            ->orderBy('sort_order')
            ->get();
    }

    public function paginateWithFilters(int $perPage = 15, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $this->model->with(['category', 'subscriptionPlans']);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('sort_order')->paginate($perPage);
    }

    public function search(string $query): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->with(['category', 'subscriptionPlans'])
            ->get();
    }

    public function slugExists(string $slug, ?string $excludeId = null): bool
    {
        $query = $this->model->where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
