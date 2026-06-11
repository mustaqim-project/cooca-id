<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 */
interface RepositoryInterface
{
    /**
     * Find a model by its primary key.
     */
    public function find(string $id): ?Model;

    /**
     * Find a model by its UUID or fail.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(string $id): Model;

    /**
     * Get all models.
     */
    public function all(): Collection;

    /**
     * Get models with pagination.
     *
     * @param array<string, mixed> $filters
     */
    public function paginate(int $perPage = 15, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * Create a new model.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): Model;

    /**
     * Update an existing model.
     *
     * @param array<string, mixed> $data
     */
    public function update(string $id, array $data): bool;

    /**
     * Delete a model.
     */
    public function delete(string $id): bool;

    /**
     * Force delete a model.
     */
    public function forceDelete(string $id): bool;

    /**
     * Restore a soft-deleted model.
     */
    public function restore(string $id): bool;

    /**
     * Get only trashed models.
     */
    public function onlyTrashed(): Collection;

    /**
     * Find a model by a specific column.
     *
     * @param string $column
     * @param mixed $value
     */
    public function findBy(string $column, mixed $value): ?Model;

    /**
     * Find first model matching conditions.
     *
     * @param array<string, mixed> $conditions
     */
    public function findFirst(array $conditions = []): ?Model;

    /**
     * Get models matching conditions.
     *
     * @param array<string, mixed> $conditions
     */
    public function where(array $conditions): Collection;

    /**
     * Count models matching conditions.
     *
     * @param array<string, mixed> $conditions
     */
    public function count(array $conditions = []): int;

    /**
     * Check if any model matches conditions.
     *
     * @param array<string, mixed> $conditions
     */
    public function exists(array $conditions = []): bool;
}
