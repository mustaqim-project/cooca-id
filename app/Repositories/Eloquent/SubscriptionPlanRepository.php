<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\SubscriptionPlan;
use App\Repositories\Contracts\SubscriptionPlanRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class SubscriptionPlanRepository extends Repository implements SubscriptionPlanRepositoryInterface
{
    public function getModel(): string
    {
        return SubscriptionPlan::class;
    }

    /**
     * @inheritDoc
     */
    public function findByProductId(string $productId): Collection
    {
        return $this->model->newQuery()
            ->where('product_id', $productId)
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getActivePlans(): Collection
    {
        return $this->model->newQuery()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getFeaturedPlans(): Collection
    {
        return $this->model->newQuery()
            ->whereHas('product', function ($query) {
                $query->where('is_featured', true);
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function findByIdAndProductId(string $planId, string $productId): ?SubscriptionPlan
    {
        return $this->model->newQuery()
            ->where('id', $planId)
            ->where('product_id', $productId)
            ->first();
    }
}
