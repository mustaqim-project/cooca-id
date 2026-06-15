<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Affiliate\RecurringCommissionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Process recurring affiliate commissions for subscription renewals
 */
class ProcessRecurringCommissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'affiliate:recurring-commissions';

    /**
     * The console command description.
     */
    protected $description = 'Process recurring affiliate commissions for subscription renewals';

    /**
     * Execute the console command.
     */
    public function handle(RecurringCommissionService $commissionService): int
    {
        $this->info('Starting recurring commission processing...');

        try {
            $processedCount = $commissionService->processRenewalCommissions();

            $this->info("Successfully processed {$processedCount} recurring commissions.");

            // Get statistics
            $stats = $commissionService->getStatistics();
            $this->info("Total renewals with affiliate: {$stats['total_renewals_with_affiliate']}");
            $this->info("Total renewal commission paid: Rp " . number_format($stats['total_renewal_commission_paid'], 0, ',', '.'));

            Log::info("Recurring commissions processed: {$processedCount} commissions", [
                'processed_count' => $processedCount,
                'statistics' => $stats,
            ]);

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to process recurring commissions: ' . $e->getMessage());
            Log::error('Failed to process recurring commissions: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);

            return self::FAILURE;
        }
    }
}
