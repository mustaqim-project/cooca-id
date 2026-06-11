<?php

declare(strict_types=1);

namespace App\Services\Subscription;

use App\Models\Subscription;
use App\Models\License;
use App\DTOs\Subscription\SubscriptionData;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Services\License\LicenseService;

final class SubscriptionService
{
    public function __construct(
        private readonly SubscriptionRepositoryInterface $subscriptionRepository,
        private readonly LicenseService $licenseService,
    ) {}

    /**
     * Create a new subscription with trial status
     */
    public function createSubscription(SubscriptionData $data): Subscription
    {
        return $this->subscriptionRepository->create($data->toArray());
    }

    /**
     * Activate a subscription
     */
    public function activateSubscription(Subscription $subscription, int $durationMonths): Subscription
    {
        $startedAt = now();
        $expiresAt = now()->addMonths($durationMonths);

        $subscription = $this->subscriptionRepository->update($subscription->id, [
            'status' => 'active',
            'started_at' => $startedAt,
            'expires_at' => $expiresAt,
        ]);

        // If subscription has a license, activate it too
        if ($subscription->license) {
            $this->licenseService->activateLicense(
                $subscription->license,
                $startedAt,
                $expiresAt
            );
        }

        return $subscription;
    }

    /**
     * Expire a subscription
     */
    public function expireSubscription(Subscription $subscription): Subscription
    {
        $subscription = $this->subscriptionRepository->update($subscription->id, [
            'status' => 'expired',
        ]);

        // Expire associated license
        if ($subscription->license) {
            $this->licenseService->expireLicense($subscription->license);
        }

        return $subscription;
    }

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(Subscription $subscription): Subscription
    {
        return $this->subscriptionRepository->update($subscription->id, [
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(Subscription $subscription): bool
    {
        if ($subscription->status !== 'active') {
            return false;
        }

        if ($subscription->expires_at && $subscription->expires_at->isPast()) {
            $this->expireSubscription($subscription);
            return false;
        }

        return true;
    }

    /**
     * Get subscription by customer and product
     */
    public function getActiveSubscriptionByCustomerAndProduct(
        \Ramsey\Uuid\UuidInterface $customerId,
        \Ramsey\Uuid\UuidInterface $productId,
    ): ?Subscription {
        return $this->subscriptionRepository->findActiveByCustomerAndProduct($customerId, $productId);
    }

    /**
     * Renew a subscription
     */
    public function renewSubscription(Subscription $subscription, int $durationMonths): Subscription
    {
        $newExpiresAt = $subscription->expires_at
            ? $subscription->expires_at->addMonths($durationMonths)
            : now()->addMonths($durationMonths);

        return $this->subscriptionRepository->update($subscription->id, [
            'status' => 'active',
            'expires_at' => $newExpiresAt,
        ]);
    }

    /**
     * Get expiring subscriptions within given days
     */
    public function getExpiringSubscriptions(int $daysFromNow): array
    {
        return $this->subscriptionRepository->findExpiringWithin($daysFromNow);
    }
}
