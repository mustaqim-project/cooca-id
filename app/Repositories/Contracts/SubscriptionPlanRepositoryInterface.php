<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Collection;

interface SubscriptionPlanRepositoryInterface extends RepositoryInterface
{
    /**
     * Find subscription plan by product ID.
     */
    public function findByProductId(string $productId): Collection;

    /**
     * Get active subscription plans.
     */
    public function getActivePlans(): Collection;

    /**
     * Get featured subscription plans.
     */
    public function getFeaturedPlans(): Collection;

    /**
     * Find plan by ID and product ID.
     */
    public function findByIdAndProductId(string $planId, string $productId): ?SubscriptionPlan;
}
