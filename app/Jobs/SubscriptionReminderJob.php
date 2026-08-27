<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Notifications\SubscriptionExpiringNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SubscriptionReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find subscriptions expiring in 7 days
        $expiringSoon = Subscription::where('status', Subscription::STATUS_ACTIVE)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays(7)])
            ->get();

        foreach ($expiringSoon as $subscription) {
            $daysRemaining = now()->diffInDays($subscription->expires_at, false);
            
            if ($daysRemaining <= 7 && $daysRemaining >= 1) {
                $subscription->customer->notify(
                    new SubscriptionExpiringNotification($subscription, $daysRemaining)
                );

                // Create activity log
                \App\Models\ActivityLog::create([
                    'user_id' => $subscription->customer_id,
                    'user_type' => 'customer',
                    'action' => 'subscription_reminder_sent',
                    'module' => 'Subscription',
                    'description' => "Subscription expiration reminder sent ({$daysRemaining} days remaining)",
                    'ip_address' => 'system',
                    'user_agent' => 'Scheduler',
                    'metadata' => [
                        'subscription_id' => $subscription->id,
                        'days_remaining' => $daysRemaining,
                    ],
                ]);
            }
        }

        // Find subscriptions expiring today
        $expiringToday = Subscription::where('status', Subscription::STATUS_ACTIVE)
            ->whereNotNull('expires_at')
            ->whereDate('expires_at', now())
            ->get();

        foreach ($expiringToday as $subscription) {
            $subscription->customer->notify(
                new SubscriptionExpiringNotification($subscription, 0)
            );
        }
    }
}
