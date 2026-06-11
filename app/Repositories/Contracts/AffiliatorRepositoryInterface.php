<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface AffiliatorRepositoryInterface extends RepositoryInterface
{
    /**
     * Find affiliator by email.
     */
    public function findByEmail(string $email): ?\App\Models\Affiliator;

    /**
     * Find affiliator by referral code.
     */
    public function findByReferralCode(string $referralCode): ?\App\Models\Affiliator;

    /**
     * Find affiliator with parent.
     */
    public function findWithParent(string $id): ?\App\Models\Affiliator;

    /**
     * Check if email exists.
     */
    public function emailExists(string $email, ?string $excludeId = null): bool;

    /**
     * Get affiliators with pagination and search.
     */
    public function paginateWithSearch(int $perPage = 15, ?string $search = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    /**
     * Get downlines (level 1).
     */
    public function getDownlines(string $affiliatorId): Collection;

    /**
     * Get all downlines recursively.
     *
     * @return array<int, \App\Models\Affiliator>
     */
    public function getAllDownlines(string $affiliatorId): array;

    /**
     * Count downlines.
     */
    public function countDownlines(string $affiliatorId): int;

    /**
     * Update balance.
     */
    public function updateBalance(string $id, float $amount): bool;

    /**
     * Get top affiliators by balance.
     */
    public function getTopAffiliators(int $limit = 10): Collection;
}
