<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Models\LicenseActivation;
use App\Notifications\SubscriptionExpiredNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpirationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find expired subscriptions
        $expired = Subscription::where('status', 'Active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->get();

        foreach ($expired as $subscription) {
            // Update subscription status
            $subscription->update(['status' => 'Expired']);

            // Suspend associated licenses
            LicenseActivation::where('subscription_id', $subscription->id)
                ->where('status', 'active')
                ->update(['status' => 'suspended']);

            // Send notification
            $subscription->customer->notify(
                new SubscriptionExpiredNotification($subscription)
            );

            // Create activity log
            \App\Models\ActivityLog::create([
                'user_id' => $subscription->customer_id,
                'user_type' => 'customer',
                'action' => 'subscription_expired',
                'module' => 'Subscription',
                'description' => 'Subscription expired and license suspended',
                'ip_address' => 'system',
                'user_agent' => 'Scheduler',
                'metadata' => [
                    'subscription_id' => $subscription->id,
                    'license_suspended' => true,
                ],
            ]);
        }
    }
}
