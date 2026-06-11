<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends RepositoryInterface
{
    /**
     * Find product by slug.
     */
    public function findBySlug(string $slug): ?\App\Models\Product;

    /**
     * Get products by category.
     */
    public function getByCategory(string $categoryId): Collection;

    /**
     * Get active products.
     */
    public function getActiveProducts(): Collection;

    /**
     * Get featured products.
     */
    public function getFeaturedProducts(): Collection;

    /**
     * Get products with pagination and filters.
     *
     * @param array<string, mixed> $filters
     */
    public function paginateWithFilters(int $perPage = 15, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * Search products.
     */
    public function search(string $query): Collection;

    /**
     * Check if slug exists.
     */
    public function slugExists(string $slug, ?string $excludeId = null): bool;
}
