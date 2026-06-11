<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface AdminRepositoryInterface extends RepositoryInterface
{
    /**
     * Find admin by email.
     */
    public function findByEmail(string $email): ?\App\Models\Admin;

    /**
     * Find admin with permissions.
     */
    public function findWithPermissions(string $id): ?\App\Models\Admin;

    /**
     * Check if email exists.
     */
    public function emailExists(string $email, ?string $excludeId = null): bool;

    /**
     * Get admins with pagination and search.
     */
    public function paginateWithSearch(int $perPage = 15, ?string $search = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * Update admin permissions.
     *
     * @param array<string, mixed> $permissions
     */
    public function updatePermissions(string $id, array $permissions): bool;

    /**
     * Get all active admins.
     */
    public function getActiveAdmins(): Collection;
}
