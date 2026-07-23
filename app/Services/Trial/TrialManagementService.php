<?php

declare(strict_types=1);

namespace App\Services\Trial;

use App\Models\Trial;
use App\Models\TrialStatusHistory;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Services\Provisioning\ProvisioningService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Service untuk mengelola lifecycle trial sesuai business rules
 */
final class TrialManagementService
{
    public function __construct(
        private readonly ProvisioningService $provisioningService
    ) {}

    /**
     * Submit trial request untuk approval
     * Business Rule: ?User dapat submit trial 1x per produk
     */
    public function submitTrialRequest(
        string $customerId,
        string $erpProductId,
        string $subscriptionPlanId,
        string $subdomain,
        ?string $affiliatorId = null
    ): Trial {
        return DB::transaction(function () use ($customerId, $erpProductId, $subscriptionPlanId, $subdomain, $affiliatorId) {
            // Cek eligibility - hanya 1 trial per customer per produk
            $existingTrial = Trial::where('customer_id', $customerId)
                ->where('erp_product_id', $erpProductId)
                ->whereIn('status', [
                    Trial::STATUS_DRAFT,
                    Trial::STATUS_SUBMITTED,
                    Trial::STATUS_WAITING_APPROVAL,
                    Trial::STATUS_ACTIVE_TRIAL,
                ])
                ->first();

            if ($existingTrial) {
                throw new \InvalidArgumentException(
                    'Customer sudah memiliki trial aktif atau pending untuk produk ini'
                );
            }

            // Validasi subdomain unik
            $existingSubdomain = Trial::where('subdomain', $subdomain)
                ->whereIn('status', [
                    Trial::STATUS_ACTIVE_TRIAL,
                    Trial::STATUS_PROVISIONING,
                    Trial::STATUS_DOMAIN_SETUP,
                    Trial::STATUS_TESTING,
                ])
                ->first();

            if ($existingSubdomain) {
                throw new \InvalidArgumentException('Subdomain sudah digunakan');
            }

            // Buat trial record
            $trial = Trial::create([
                'customer_id' => $customerId,
                'erp_product_id' => $erpProductId,
                'subscription_plan_id' => $subscriptionPlanId,
                'subdomain' => $subdomain,
                'referred_by_id' => $affiliatorId,
                'status' => Trial::STATUS_WAITING_APPROVAL,
                'submitted_at' => now(),
            ]);

            // Record status history
            $trial->recordStatusChange(
                Trial::STATUS_WAITING_APPROVAL,
                'Trial submitted for approval',
                $customerId,
                Customer::class
            );

            Log::info("Trial submitted", [
                'trial_id' => $trial->id,
                'customer_id' => $customerId,
                'product_id' => $erpProductId,
            ]);

            return $trial;
        });
    }

    /**
     * Approve trial request (Admin only)
     * Trigger provisioning setup
     */
    public function approveTrial(string $trialId, string $adminId): Trial
    {
        return DB::transaction(function () use ($trialId, $adminId) {
            $trial = Trial::findOrFail($trialId);

            if ($trial->status !== Trial::STATUS_WAITING_APPROVAL) {
                throw new \InvalidArgumentException(
                    "Trial tidak dapat diapprove. Status saat ini: {$trial->status}"
                );
            }

            $fromStatus = $trial->status;
            $trial->update([
                'status' => Trial::STATUS_WAITING_PROVISIONING,
                'approved_at' => now(),
            ]);

            // Record status history
            $trial->recordStatusChange(
                Trial::STATUS_WAITING_PROVISIONING,
                'Trial approved by admin',
                $adminId,
                'App\\Models\\Admin'
            );

            // Trigger provisioning
            try {
                $provisioningJob = $this->provisioningService->provisionTrial($trial);
                
                // Run provisioning asynchronously via queue or synchronously based on config
                if (config('app.sync_provisioning', false)) {
                    $this->provisioningService->runProvisioning($provisioningJob);
                } else {
                    // Dispatch to queue - will be processed by queue worker
                    \App\Jobs\Provisioning\RunProvisioningJob::dispatch($provisioningJob);
                }
            } catch (\Exception $e) {
                Log::error("Provisioning failed for trial {$trialId}", [
                    'error' => $e->getMessage(),
                ]);
                
                $trial->update(['status' => Trial::STATUS_FAILED]);
                $trial->recordStatusChange(
                    Trial::STATUS_FAILED,
                    'Provisioning failed: ' . $e->getMessage(),
                    $adminId,
                    'App\\Models\\Admin'
                );
                
                throw $e;
            }

            // Dispatch notification job
            \App\Jobs\Notification\SendTrialApprovedNotificationJob::dispatch($trial);

            Log::info("Trial approved", [
                'trial_id' => $trial->id,
                'admin_id' => $adminId,
            ]);

            return $trial;
        });
    }

    /**
     * Reject trial request (Admin only)
     */
    public function rejectTrial(string $trialId, string $adminId, string $reason): Trial
    {
        return DB::transaction(function () use ($trialId, $adminId, $reason) {
            $trial = Trial::findOrFail($trialId);

            if ($trial->status !== Trial::STATUS_WAITING_APPROVAL) {
                throw new \InvalidArgumentException(
                    "Trial tidak dapat direject. Status saat ini: {$trial->status}"
                );
            }

            $trial->update([
                'status' => Trial::STATUS_REJECTED,
                'rejection_reason' => $reason,
            ]);

            $trial->recordStatusChange(
                Trial::STATUS_REJECTED,
                "Rejected: {$reason}",
                $adminId,
                'App\\Models\\Admin'
            );

            // Dispatch notification job
            \App\Jobs\Notification\SendTrialRejectedNotificationJob::dispatch($trial);

            Log::info("Trial rejected", [
                'trial_id' => $trial->id,
                'admin_id' => $adminId,
                'reason' => $reason,
            ]);

            return $trial;
        });
    }

    /**
     * Start trial period setelah provisioning selesai
     */
    public function startTrialPeriod(string $trialId, int $durationDays = 14): Trial
    {
        return DB::transaction(function () use ($trialId, $durationDays) {
            $trial = Trial::findOrFail($trialId);

            if (!in_array($trial->status, [Trial::STATUS_PROVISIONING, Trial::STATUS_DOMAIN_SETUP, Trial::STATUS_TESTING])) {
                throw new \InvalidArgumentException(
                    "Trial belum siap dimulai. Status saat ini: {$trial->status}"
                );
            }

            $expiresAt = now()->addDays($durationDays);

            $trial->update([
                'status' => Trial::STATUS_ACTIVE_TRIAL,
                'started_at' => now(),
                'expires_at' => $expiresAt,
            ]);

            $trial->recordStatusChange(
                Trial::STATUS_ACTIVE_TRIAL,
                "Trial started. Duration: {$durationDays} days",
                system: true,
                actorType: 'system'
            );

            // Dispatch notification job
            \App\Jobs\Notification\SendTrialStartedNotificationJob::dispatch($trial);

            Log::info("Trial period started", [
                'trial_id' => $trial->id,
                'expires_at' => $expiresAt->toIso8601String(),
            ]);

            return $trial;
        });
    }

    /**
     * Convert trial to subscription
     */
    public function convertToSubscription(string $trialId, string $subscriptionId): Trial
    {
        return DB::transaction(function () use ($trialId, $subscriptionId) {
            $trial = Trial::findOrFail($trialId);

            if ($trial->status !== Trial::STATUS_ACTIVE_TRIAL) {
                throw new \InvalidArgumentException(
                    "Hanya active trial yang dapat dikonversi. Status saat ini: {$trial->status}"
                );
            }

            $trial->update([
                'status' => Trial::STATUS_CONVERTED_TO_SUBSCRIPTION,
                'subscription_id' => $subscriptionId,
                'converted_at' => now(),
            ]);

            $trial->recordStatusChange(
                Trial::STATUS_CONVERTED_TO_SUBSCRIPTION,
                "Converted to subscription: {$subscriptionId}",
                system: true,
                actorType: 'system'
            );

            Log::info("Trial converted to subscription", [
                'trial_id' => $trial->id,
                'subscription_id' => $subscriptionId,
            ]);

            return $trial;
        });
    }

    /**
     * Expire trial yang sudah melewati tanggal expired
     * Dipanggil oleh scheduler daily
     */
    public function expireOverdueTrials(): int
    {
        $expiredCount = 0;

        $trials = Trial::where('status', Trial::STATUS_ACTIVE_TRIAL)
            ->where('expires_at', '<', now())
            ->get();

        foreach ($trials as $trial) {
            DB::transaction(function () use ($trial, &$expiredCount) {
                $trial->update(['status' => Trial::STATUS_EXPIRED]);

                $trial->recordStatusChange(
                    Trial::STATUS_EXPIRED,
                    'Trial expired automatically',
                    system: true,
                    actorType: 'system'
                );

                // Dispatch notification job
                \App\Jobs\Notification\SendTrialExpiredNotificationJob::dispatch($trial);

                $expiredCount++;
            });
        }

        Log::info("Expired trials processed", ['count' => $expiredCount]);

        return $expiredCount;
    }

    /**
     * Get trials yang akan expire dalam X hari
     * Untuk reminder notification
     */
    public function getExpiringTrials(int $withinDays): array
    {
        return Trial::where('status', Trial::STATUS_ACTIVE_TRIAL)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays($withinDays)])
            ->with(['customer', 'erpProduct', 'subscriptionPlan'])
            ->get()
            ->toArray();
    }

    /**
     * Update trial status ke testing phase
     */
    public function markAsTesting(string $trialId): Trial
    {
        $trial = Trial::findOrFail($trialId);

        if (!in_array($trial->status, [Trial::STATUS_PROVISIONING, Trial::STATUS_DOMAIN_SETUP])) {
            throw new \InvalidArgumentException(
                "Trial belum siap untuk testing. Status saat ini: {$trial->status}"
            );
        }

        $trial->update(['status' => Trial::STATUS_TESTING]);

        $trial->recordStatusChange(
            Trial::STATUS_TESTING,
            'Ready for customer testing',
            system: true,
            actorType: 'system'
        );

        return $trial;
    }

    /**
     * Update trial status ke domain_setup phase
     */
    public function markAsDomainSetup(string $trialId): Trial
    {
        $trial = Trial::findOrFail($trialId);

        if ($trial->status !== Trial::STATUS_PROVISIONING) {
            throw new \InvalidArgumentException(
                "Trial belum siap untuk domain setup. Status saat ini: {$trial->status}"
            );
        }

        $trial->update(['status' => Trial::STATUS_DOMAIN_SETUP]);

        $trial->recordStatusChange(
            Trial::STATUS_DOMAIN_SETUP,
            'Waiting for DNS/SSL setup',
            system: true,
            actorType: 'system'
        );

        return $trial;
    }
}


