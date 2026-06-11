<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface SubscriptionRepositoryInterface extends RepositoryInterface
{
    /**
     * Get subscriptions by customer.
     */
    public function getByCustomer(string $customerId): Collection;

    /**
     * Get active subscriptions by customer.
     */
    public function getActiveByCustomer(string $customerId): Collection;

    /**
     * Get subscriptions by license.
     */
    public function getByLicense(string $licenseId): Collection;

    /**
     * Get subscriptions by plan.
     */
    public function getByPlan(string $planId): Collection;

    /**
     * Get expiring subscriptions.
     */
    public function getExpiringSoon(int $days = 7): Collection;

    /**
     * Get expired subscriptions.
     */
    public function getExpiredSubscriptions(): Collection;

    /**
     * Count active subscriptions.
     */
    public function countActive(): int;

    /**
     * Check if customer has active subscription for product.
     */
    public function customerHasActiveSubscription(string $customerId, string $productId): bool;
}
