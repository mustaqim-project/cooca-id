<?php

namespace App\Console\Commands;

use App\Jobs\SubscriptionReminderJob;
use App\Jobs\SubscriptionExpirationJob;
use App\Services\Affiliate\SettlementService;
use App\Services\Affiliate\RecurringCommissionService;
use Illuminate\Console\Command;

class ProcessSubscriptionJobs extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'subscriptions:process';

    /**
     * The console command description.
     */
    protected $description = 'Process subscription reminders, expirations, and affiliate settlements';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting subscription processing...');

        // Process subscription reminders
        $this->info('Processing subscription reminders...');
        SubscriptionReminderJob::dispatchSync();
        $this->info('Subscription reminders processed.');

        // Process subscription expirations
        $this->info('Processing subscription expirations...');
        SubscriptionExpirationJob::dispatchSync();
        $this->info('Subscription expirations processed.');

        // Process affiliate commission settlements
        $this->info('Processing affiliate commission settlements...');
        $settlementService = app(SettlementService::class);
        $settledCount = $settlementService->settlePendingCommissions();
        $this->info("Settled {$settledCount} pending commissions.");

        // Process recurring commissions
        $this->info('Processing recurring commissions...');
        $recurringService = app(RecurringCommissionService::class);
        $recurringCount = $recurringService->processRenewalCommissions();
        $this->info("Processed {$recurringCount} recurring commissions.");

        $this->info('All subscription processing completed successfully!');

        return self::SUCCESS;
    }
}
