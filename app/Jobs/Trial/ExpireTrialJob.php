<?php

declare(strict_types=1);

namespace App\Jobs\Trial;

use App\Models\Subscription;
use App\Models\LicenseActivation;
use App\Notifications\TrialExpiringSoonNotification;
use App\Notifications\TrialExpiredNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job to expire trials that have passed their trial period
 */
class ExpireTrialJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = now();
        $expiredCount = 0;
        $warning3DaysCount = 0;
        $warning1DayCount = 0;

        // Find trials expiring in 3 days (H-3 notification)
        $trialsExpiringIn3Days = Subscription::where('status', Subscription::STATUS_TRIAL)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [$now->copy()->addDays(2), $now->copy()->addDays(3)])
            ->get();

        foreach ($trialsExpiringIn3Days as $subscription) {
            $this->sendTrialExpiringNotification($subscription, 3);
            $warning3DaysCount++;
        }

        // Find trials expiring in 1 day (H-1 notification)
        $trialsExpiringIn1Day = Subscription::where('status', Subscription::STATUS_TRIAL)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [$now->copy()->addHours(12), $now->copy()->addDay()])
            ->get();

        foreach ($trialsExpiringIn1Day as $subscription) {
            $this->sendTrialExpiringNotification($subscription, 1);
            $warning1DayCount++;
        }

        // Find expired trials
        $expiredTrials = Subscription::where('status', Subscription::STATUS_TRIAL)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', $now)
            ->get();

        foreach ($expiredTrials as $subscription) {
            $this->expireTrial($subscription);
            $expiredCount++;
        }

        // Create activity log
        \App\Models\ActivityLog::create([
            'causer_id' => null,
            'causer_type' => 'system',
            'action' => 'trial_expiration_processed',
            'module' => 'subscription',
            'description' => "Processed trial expirations: {$expiredCount} expired, {$warning3DaysCount} H-3 warnings, {$warning1DayCount} H-1 warnings",
            'ip_address' => 'system',
            'user_agent' => 'Scheduler',
            'metadata' => [
                'expired_count' => $expiredCount,
                'warning_3_days_count' => $warning3DaysCount,
                'warning_1_day_count' => $warning1DayCount,
                'processed_at' => $now->toIso8601String(),
            ],
        ]);

        Log::info("Trial expiration job completed: {$expiredCount} expired, {$warning3DaysCount} H-3 warnings, {$warning1DayCount} H-1 warnings");
    }

    /**
     * Expire a trial subscription
     */
    private function expireTrial(Subscription $subscription): void
    {
        try {
            // Update subscription status to TrialExpired
            $subscription->update([
                'status' => Subscription::STATUS_EXPIRED,
                'cancelled_at' => now(),
            ]);

            // Suspend associated licenses
            LicenseActivation::where('subscription_id', $subscription->id)
                ->where('status', 'active')
                ->update(['status' => 'suspended']);

            // Send expiration notification (H+1)
            if ($subscription->customer) {
                $subscription->customer->notify(new TrialExpiredNotification($subscription));
            }

            // Create activity log for this specific subscription
            \App\Models\ActivityLog::create([
                'causer_id' => $subscription->customer_id ?? null,
                'causer_type' => 'customer',
                'action' => 'trial_expired',
                'module' => 'subscription',
                'description' => "Trial expired for subscription {$subscription->id}",
                'ip_address' => 'system',
                'user_agent' => 'Scheduler',
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'customer_id' => $subscription->customer_id,
                    'license_suspended' => true,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to expire trial subscription {$subscription->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send trial expiring soon notification
     */
    private function sendTrialExpiringNotification(Subscription $subscription, int $daysUntilExpiry): void
    {
        try {
            if ($subscription->customer) {
                $subscription->customer->notify(
                    new TrialExpiringSoonNotification($subscription, $daysUntilExpiry)
                );
            }

            \App\Models\ActivityLog::create([
                'causer_id' => $subscription->customer_id ?? null,
                'causer_type' => 'customer',
                'action' => 'trial_expiry_warning_sent',
                'module' => 'subscription',
                'description' => "Sent H-{$daysUntilExpiry} trial expiry warning for subscription {$subscription->id}",
                'ip_address' => 'system',
                'user_agent' => 'Scheduler',
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'customer_id' => $subscription->customer_id,
                    'days_until_expiry' => $daysUntilExpiry,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send trial expiry notification for subscription {$subscription->id}: " . $e->getMessage());
        }
    }
}
