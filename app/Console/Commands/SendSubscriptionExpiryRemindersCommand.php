<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Jobs\Notification\SendSubscriptionExpiryReminderJob;
use Illuminate\Console\Command;

final class SendSubscriptionExpiryRemindersCommand extends Command
{
    protected $signature = 'subscriptions:remind {--days=7}';
    protected $description = 'Send subscription expiry reminders for specified days before expiry';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        
        $this->info("Sending subscription expiry reminders (H-{$days})...");

        $targetDate = now()->addDays($days)->startOfDay();
        
        $subscriptions = Subscription::where('status', 'active')
            ->whereDate('ends_at', $targetDate)
            ->with(['customer', 'license.product'])
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info("No subscriptions expiring in {$days} days.");
            return self::SUCCESS;
        }

        $count = 0;

        foreach ($subscriptions as $subscription) {
            try {
                SendSubscriptionExpiryReminderJob::dispatch(
                    $subscription->customer,
                    $subscription,
                    $days
                );

                $this->line("✓ Sent H-{$days} reminder to {$subscription->customer->email}");
                $count++;
            } catch (\Throwable $e) {
                report($e);
                $this->error("Failed to send H-{$days} reminder to {$subscription->customer->email}");
            }
        }

        $this->info("Total reminders sent: {$count}");

        return self::SUCCESS;
    }
}
