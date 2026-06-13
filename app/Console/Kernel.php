
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Process subscriptions daily at 8 AM
        $schedule->command('subscriptions:process')
            ->dailyAt('08:00')
            ->timezone('Asia/Jakarta');
    }
