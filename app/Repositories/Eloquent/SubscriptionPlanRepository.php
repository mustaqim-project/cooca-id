<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\SubscriptionPlan;
use App\Repositories\Contracts\SubscriptionPlanRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class SubscriptionPlanRepository extends BaseRepository implements SubscriptionPlanRepositoryInterface
{
    public function __construct(SubscriptionPlan $model)
    {
        parent::__construct($model);
    }

    public function getActivePlansByProduct(string $productId): Collection
    {
        return $this->model
            ->where('product_id', $productId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();
    }

    public function getActivePlanById(string $id): ?SubscriptionPlan
    {
        return $this->model
            ->where('id', $id)
            ->where('is_active', true)
            ->first();
    }

    public function getPlansWithDuration(int $durationMonths): Collection
    {
        return $this->model
            ->where('duration_months', $durationMonths)
            ->where('is_active', true)
            ->orderBy('price')
            ->get();
    }

    public function calculateDiscountedPrice(SubscriptionPlan $plan): float
    {
        if ($plan->discount_percent <= 0) {
            return (float) $plan->price;
        }

        $discount = $plan->price * ($plan->discount_percent / 100);
        return (float) ($plan->price - $discount);
    }

    public function getFeaturedPlans(int $limit = 6): Collection
    {
        return $this->model
            ->whereHas('product', function ($query) {
                $query->where('is_featured', true);
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }
}
