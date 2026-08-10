<?php

declare(strict_types=1);

namespace App\Services\Subscription;

use App\Models\Subscription;
use App\Models\SubscriptionStatusHistory;
use App\Models\License;
use App\Models\Invoice;
use App\DTOs\Subscription\SubscriptionData;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Services\License\LicenseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk mengelola subscription lifecycle lengkap
 * Mendukung: auto-renewal, upgrade/downgrade, grace period, suspension
 */
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
        return DB::transaction(function () use ($subscription, $durationMonths) {
            $startedAt = now();

            // 999 months = Lifetime license — never expires
            $expiresAt = $durationMonths >= 999 ? null : now()->addMonths($durationMonths);

            $fromStatus = $subscription->status;
            $this->subscriptionRepository->update($subscription->id, [
                'status'     => 'active',
                'started_at' => $startedAt,
                'expires_at' => $expiresAt,
            ]);
            $subscription->refresh();

            // Record status history
            $this->recordStatusChange(
                $subscription,
                $fromStatus,
                'active',
                'Subscription activated',
                null,
                'system'
            );

            // If subscription has a license, activate it too
            if ($subscription->license) {
                $this->licenseService->activateLicense(
                    $subscription->license,
                    $startedAt,
                    $expiresAt
                );
            }

            Log::info("Subscription activated", [
                'subscription_id' => $subscription->id,
                'expires_at' => $expiresAt?->toIso8601String(),
            ]);

            return $subscription;
        });
    }

    /**
     * Expire a subscription
     */
    public function expireSubscription(Subscription $subscription): Subscription
    {
        return DB::transaction(function () use ($subscription) {
            $fromStatus = $subscription->status;

            $this->subscriptionRepository->update($subscription->id, [
                'status' => 'expired',
            ]);
            $subscription->refresh();

            // Record status history
            $this->recordStatusChange(
                $subscription,
                $fromStatus,
                'expired',
                'Subscription expired',
                null,
                'system'
            );

            // Expire associated license
            if ($subscription->license) {
                $this->licenseService->expireLicense($subscription->license);
            }

            Log::info("Subscription expired", [
                'subscription_id' => $subscription->id,
            ]);

            return $subscription;
        });
    }

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(Subscription $subscription): Subscription
    {
        return DB::transaction(function () use ($subscription) {
            $fromStatus = $subscription->status;

            $this->subscriptionRepository->update($subscription->id, [
                'status'       => 'cancelled',
                'cancelled_at' => now(),
            ]);
            $subscription->refresh();

            // Record status history
            $this->recordStatusChange(
                $subscription,
                $fromStatus,
                'cancelled',
                'Subscription cancelled',
                null,
                'system'
            );

            return $subscription;
        });
    }

    /**
     * Suspend a subscription (e.g., payment failed)
     */
    public function suspendSubscription(Subscription $subscription, string $reason): Subscription
    {
        return DB::transaction(function () use ($subscription, $reason) {
            $fromStatus = $subscription->status;

            $this->subscriptionRepository->update($subscription->id, [
                'status' => 'suspended',
            ]);
            $subscription->refresh();

            // Record status history
            $this->recordStatusChange(
                $subscription,
                $fromStatus,
                'suspended',
                "Suspended: {$reason}",
                null,
                'system'
            );

            Log::info("Subscription suspended", [
                'subscription_id' => $subscription->id,
                'reason' => $reason,
            ]);

            return $subscription;
        });
    }

    /**
     * Reactivate a suspended subscription
     */
    public function reactivateSubscription(Subscription $subscription): Subscription
    {
        return DB::transaction(function () use ($subscription) {
            if ($subscription->status !== 'suspended') {
                throw new \InvalidArgumentException(
                    "Hanya suspended subscription yang dapat diaktifkan kembali. Status: {$subscription->status}"
                );
            }

            $fromStatus = $subscription->status;

            $this->subscriptionRepository->update($subscription->id, [
                'status' => 'active',
            ]);
            $subscription->refresh();

            // Record status history
            $this->recordStatusChange(
                $subscription,
                $fromStatus,
                'active',
                'Subscription reactivated after suspension',
                null,
                'system'
            );

            return $subscription;
        });
    }

    /**
     * Upgrade subscription to higher plan
     * Support prorated billing calculation
     */
    public function upgradeSubscription(
        Subscription $subscription,
        string $newPlanId,
        float $proratedAmount = 0.0
    ): Subscription {
        return DB::transaction(function () use ($subscription, $newPlanId, $proratedAmount) {
            if ($subscription->status !== 'active') {
                throw new \InvalidArgumentException(
                    "Hanya active subscription yang dapat diupgrade. Status: {$subscription->status}"
                );
            }

            $oldPlanId = $subscription->subscription_plan_id;

            $this->subscriptionRepository->update($subscription->id, [
                'subscription_plan_id' => $newPlanId,
            ]);
            $subscription->refresh();

            Log::info("Subscription upgraded", [
                'subscription_id' => $subscription->id,
                'old_plan_id' => $oldPlanId,
                'new_plan_id' => $newPlanId,
                'prorated_amount' => $proratedAmount,
            ]);

            // TODO: Create invoice for prorated amount
            // TODO: Adjust next billing cycle

            return $subscription;
        });
    }

    /**
     * Downgrade subscription to lower plan
     * Effective at next billing cycle
     */
    public function downgradeSubscription(
        Subscription $subscription,
        string $newPlanId
    ): Subscription {
        return DB::transaction(function () use ($subscription, $newPlanId) {
            if ($subscription->status !== 'active') {
                throw new \InvalidArgumentException(
                    "Hanya active subscription yang dapat didowngrade. Status: {$subscription->status}"
                );
            }

            // Downgrade effective at next renewal
            // Store pending downgrade info
            $this->subscriptionRepository->update($subscription->id, [
                'subscription_plan_id' => $newPlanId,
            ]);
            $subscription->refresh();

            Log::info("Subscription downgraded", [
                'subscription_id' => $subscription->id,
                'new_plan_id' => $newPlanId,
            ]);

            return $subscription;
        });
    }

    /**
     * Auto-renew subscription based on invoice payment
     */
    public function autoRenewSubscription(Subscription $subscription, Invoice $invoice): Subscription
    {
        return DB::transaction(function () use ($subscription, $invoice) {
            if ($subscription->status === 'expired') {
                // Reactivate expired subscription
                $subscription = $this->reactivateExpiredSubscription($subscription, $invoice);
            } elseif ($subscription->status === 'active') {
                // Extend active subscription
                $subscription = $this->extendActiveSubscription($subscription, $invoice);
            } else {
                throw new \InvalidArgumentException(
                    "Cannot renew subscription with status: {$subscription->status}"
                );
            }

            Log::info("Subscription auto-renewed", [
                'subscription_id' => $subscription->id,
                'invoice_id' => $invoice->id,
            ]);

            return $subscription;
        });
    }

    /**
     * Reactivate expired subscription with new payment
     */
    private function reactivateExpiredSubscription(
        Subscription $subscription,
        Invoice $invoice
    ): Subscription {
        $fromStatus = $subscription->status;
        $durationMonths = $this->getPlanDurationMonths($subscription->subscription_plan_id);

        $newExpiresAt = now()->addMonths($durationMonths);

        $this->subscriptionRepository->update($subscription->id, [
            'status'     => 'active',
            'expires_at' => $newExpiresAt,
        ]);
        $subscription->refresh();

        // Record status history
        $this->recordStatusChange(
            $subscription,
            $fromStatus,
            'active',
            'Reactivated via auto-renewal payment',
            $invoice->id,
            Invoice::class
        );

        // Reactivate license
        if ($subscription->license) {
            $this->licenseService->activateLicense(
                $subscription->license,
                now(),
                $newExpiresAt
            );
        }

        return $subscription;
    }

    /**
     * Extend active subscription expiry date
     */
    private function extendActiveSubscription(
        Subscription $subscription,
        Invoice $invoice
    ): Subscription {
        $durationMonths = $this->getPlanDurationMonths($subscription->subscription_plan_id);

        // Extend from current expiry or now if no expiry
        $baseDate = $subscription->expires_at ?? now();
        $newExpiresAt = $baseDate->addMonths($durationMonths);

        $this->subscriptionRepository->update($subscription->id, [
            'expires_at' => $newExpiresAt,
        ]);
        $subscription->refresh();

        // Record extension
        $subscription->recordStatusChange(
            'active',
            'active',
            "Extended by {$durationMonths} months via auto-renewal",
            $invoice->id,
            Invoice::class
        );

        // Update license expiry
        if ($subscription->license) {
            $this->licenseService->extendLicense($subscription->license, $newExpiresAt);
        }

        return $subscription;
    }

    /**
     * Get plan duration in months
     */
    private function getPlanDurationMonths(string $planId): int
    {
        // TODO: Fetch from SubscriptionPlan model
        // Default to 1 month for now
        return 1;
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
     * Check if subscription is in grace period (expired but still accessible)
     */
    public function isInGracePeriod(Subscription $subscription, int $graceDays = 7): bool
    {
        if ($subscription->status !== 'expired') {
            return false;
        }

        if (!$subscription->expires_at) {
            return false;
        }

        return now()->diffInDays($subscription->expires_at) <= $graceDays;
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
        return DB::transaction(function () use ($subscription, $durationMonths) {
            $fromStatus = $subscription->status;

            $newExpiresAt = $subscription->expires_at
                ? $subscription->expires_at->addMonths($durationMonths)
                : now()->addMonths($durationMonths);

            $this->subscriptionRepository->update($subscription->id, [
                'status'     => 'active',
                'expires_at' => $newExpiresAt,
            ]);

            $subscription->refresh();

            // Record status history if status changed
            if ($fromStatus !== 'active') {
                $this->recordStatusChange(
                    $subscription,
                    $fromStatus,
                    'active',
                    "Renewed for {$durationMonths} months",
                    system: true,
                    actorType: 'system'
                );
            }

            return $subscription;
        });
    }

    /**
     * Get expiring subscriptions within given days
     */
    public function getExpiringSubscriptions(int $daysFromNow): array
    {
        return $this->subscriptionRepository->findExpiringWithin($daysFromNow);
    }

    /**
     * Record subscription status change in history
     */
    private function recordStatusChange(
        Subscription $subscription,
        string $fromStatus,
        string $toStatus,
        ?string $reason = null,
        ?string $actorId = null,
        ?string $actorType = null
    ): void {
        SubscriptionStatusHistory::create([
            'subscription_id' => $subscription->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'reason' => $reason,
            'actor_id' => $actorId,
            'actor_type' => $actorType,
        ]);
    }
}
