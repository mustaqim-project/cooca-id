<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface AffiliateCommissionRepositoryInterface extends RepositoryInterface
{
    /**
     * Get commissions by affiliator.
     */
    public function getByAffiliator(string $affiliatorId): Collection;

    /**
     * Get pending commissions by affiliator.
     */
    public function getPendingByAffiliator(string $affiliatorId): Collection;

    /**
     * Get cleared commissions by affiliator.
     */
    public function getClearedByAffiliator(string $affiliatorId): Collection;

    /**
     * Calculate total commission by affiliator and status.
     */
    public function getTotalByAffiliatorAndStatus(string $affiliatorId, string $status): float;

    /**
     * Calculate pending commission balance.
     */
    public function calculatePendingBalance(string $affiliatorId): float;

    /**
     * Mark commissions as cleared.
     *
     * @param array<int, string> $commissionIds
     */
    public function markAsCleared(array $commissionIds): bool;

    /**
     * Get commissions by transaction.
     */
    public function getByTransaction(string $transactionId): Collection;

    /**
     * Get level 1 commissions.
     */
    public function getLevel1Commissions(): Collection;

    /**
     * Get level 2 commissions.
     */
    public function getLevel2Commissions(): Collection;
}
