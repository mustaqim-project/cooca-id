<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CustomerRepositoryInterface extends RepositoryInterface
{
    /**
     * Find customer by email.
     */
    public function findByEmail(string $email): ?\App\Models\Customer;

    /**
     * Find customer with affiliator.
     */
    public function findWithAffiliator(string $id): ?\App\Models\Customer;

    /**
     * Check if email exists.
     */
    public function emailExists(string $email, ?string $excludeId = null): bool;

    /**
     * Get customers with pagination and search.
     */
    public function paginateWithSearch(int $perPage = 15, ?string $search = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * Get customers by affiliator.
     */
    public function getByAffiliator(string $affiliatorId): Collection;

    /**
     * Count customers by affiliator.
     */
    public function countByAffiliator(string $affiliatorId): int;

    /**
     * Get active customers.
     */
    public function getActiveCustomers(): Collection;
}
