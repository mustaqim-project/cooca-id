<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Subscription;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

final class SubscriptionRepository extends BaseRepository implements SubscriptionRepositoryInterface
{
    public function __construct(Subscription $model)
    {
        parent::__construct($model);
    }

    public function findByCustomerId(string $customerId): Collection
    {
        return $this->model
            ->where('customer_id', $customerId)
            ->with(['license.product', 'subscriptionPlan'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getActiveSubscriptions(): Collection
    {
        return $this->model
            ->where('status', 'active')
            ->with(['customer', 'license.product', 'subscriptionPlan'])
            ->orderBy('expires_at')
            ->get();
    }

    public function getExpiringSubscriptions(int $daysUntilExpiry = 7): Collection
    {
        $expiryDate = Carbon::now()->addDays($daysUntilExpiry);

        return $this->model
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $expiryDate)
            ->with(['customer', 'license.product'])
            ->orderBy('expires_at')
            ->get();
    }

    public function getExpiredSubscriptions(): Collection
    {
        return $this->model
            ->where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', Carbon::now())
            ->with(['customer', 'license.product'])
            ->get();
    }

    public function markAsExpired(string $subscriptionId): bool
    {
        $subscription = $this->model->find($subscriptionId);
        
        if (!$subscription) {
            return false;
        }

        $subscription->update([
            'status' => 'expired',
            'cancelled_at' => Carbon::now(),
        ]);

        return true;
    }

    public function cancelSubscription(string $subscriptionId): bool
    {
        $subscription = $this->model->find($subscriptionId);
        
        if (!$subscription) {
            return false;
        }

        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => Carbon::now(),
        ]);

        return true;
    }

    public function activateSubscription(string $subscriptionId, Carbon $startDate, Carbon $endDate): bool
    {
        $subscription = $this->model->find($subscriptionId);
        
        if (!$subscription) {
            return false;
        }

        $subscription->update([
            'status' => 'active',
            'started_at' => $startDate,
            'expires_at' => $endDate,
        ]);

        return true;
    }

    public function getSubscriptionByLicenseId(string $licenseId): ?Subscription
    {
        return $this->model
            ->where('license_id', $licenseId)
            ->where('status', 'active')
            ->with(['license', 'subscriptionPlan'])
            ->first();
    }
}
