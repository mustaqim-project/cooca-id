<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Process subscriptions daily at 8 AM
        $schedule->command('subscriptions:process')
            ->dailyAt('08:00')
            ->timezone('Asia/Jakarta');

        // Expire trials - runs every hour to check for expired trials and send notifications
        $schedule->job(\App\Jobs\Trial\ExpireTrialJob::class)
            ->hourly()
            ->timezone('Asia/Jakarta');

        // Process trial expiration daily at 7 AM
        $schedule->command('trials:process-expiration')
            ->dailyAt('07:00')
            ->timezone('Asia/Jakarta');

        // Process recurring affiliate commissions daily at 9 AM
        $schedule->command('affiliate:recurring-commissions')
            ->dailyAt('09:00')
            ->timezone('Asia/Jakarta');

        // Process commission holding period (14 days) daily at 6 AM
        $schedule->command('commissions:process-holding')
            ->dailyAt('06:00')
            ->timezone('Asia/Jakarta');

        // Send subscription expiry reminders daily at 10 AM
        $schedule->command('subscriptions:send-expiry-reminders')
            ->dailyAt('10:00')
            ->timezone('Asia/Jakarta');

        // Auto end inactive live chat sessions (2-minute timeout)
        $schedule->command('live-chats:auto-end')->everyMinute();

        // Monitor active tenant subdomains every 5 minutes
        $schedule->command('tenants:monitor')
            ->everyFiveMinutes()
            ->timezone('Asia/Jakarta');

        // Database backups
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->daily()->at('01:30');
    }
}
