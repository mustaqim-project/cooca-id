<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\Notification\SendSubscriptionExpiryReminderJob;
use App\Models\Subscription;
use Illuminate\Console\Command;

final class SendSubscriptionExpiryRemindersCommand extends Command
{
    protected $signature = 'subscriptions:send-reminders';
    protected $description = 'Send subscription expiry reminders (H-7 and H-1)';

    public function handle(): int
    {
        $this->info('Sending subscription expiry reminders...');

        // H-7 reminder
        $sevenDaysBefore = now()->addDays(7)->startOfDay();
        $subscriptions7Days = Subscription::where('status', 'active')
            ->whereDate('expires_at', $sevenDaysBefore)
            ->with(['customer', 'license.product'])
            ->get();

        foreach ($subscriptions7Days as $subscription) {
            try {
                SendSubscriptionExpiryReminderJob::dispatch(
                    $subscription->customer,
                    $subscription,
                    7
                );

                $this->line("✓ Sent H-7 reminder to {$subscription->customer->email}");
            } catch (\Throwable $e) {
                report($e);
                $this->error("Failed to send H-7 reminder to {$subscription->customer->email}");
            }
        }

        // H-1 reminder
        $oneDayBefore = now()->addDays(1)->startOfDay();
        $subscriptions1Day = Subscription::where('status', 'active')
            ->whereDate('expires_at', $oneDayBefore)
            ->with(['customer', 'license.product'])
            ->get();

        foreach ($subscriptions1Day as $subscription) {
            try {
                SendSubscriptionExpiryReminderJob::dispatch(
                    $subscription->customer,
                    $subscription,
                    1
                );

                $this->line("✓ Sent H-1 reminder to {$subscription->customer->email}");
            } catch (\Throwable $e) {
                report($e);
                $this->error("Failed to send H-1 reminder to {$subscription->customer->email}");
            }
        }

        $totalSent = $subscriptions7Days->count() + $subscriptions1Day->count();
        $this->info("Total reminders sent: {$totalSent}");

        return Command::SUCCESS;
    }
}
