<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Affiliate\AffiliateService;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ClearPendingCommissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'affiliate:clear-commissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear pending affiliate commissions after 14 days holding period';

    /**
     * Execute the console command.
     */
    public function handle(AffiliateService $affiliateService): int
    {
        $this->info('Starting clear pending commissions job...');
        
        try {
            // Commissions older than 14 days
            $cutoffDate = Carbon::now()->subDays(14);
            
            $clearedCount = $affiliateService->clearCommissions($cutoffDate);
            
            $this->info("Successfully cleared {$clearedCount} pending commissions.");
            Log::info("ClearPendingCommissionsCommand: Cleared {$clearedCount} commissions.");
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to clear commissions: ' . $e->getMessage());
            Log::error('ClearPendingCommissionsCommand failed', [
                'error' => $e->getMessage()
            ]);
            
            return Command::FAILURE;
        }
    }
}
