<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Trial\TrialManagementService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Command untuk memproses trial expiration daily
 * Dipanggil oleh scheduler setiap hari
 */
class ProcessTrialExpirationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trials:process-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process trial expirations and send expiry warnings';

    public function __construct(
        private readonly TrialManagementService $trialManagementService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Processing trial expirations...');

        try {
            // Expire overdue trials
            $expiredCount = $this->trialManagementService->expireOverdueTrials();
            $this->info("Expired {$expiredCount} overdue trials");

            // Get expiring trials for reminder (3 days before expiry)
            $expiringTrials = $this->trialManagementService->getExpiringTrials(3);
            $this->info("Found " . count($expiringTrials) . " trials expiring within 3 days");

            // TODO: Send notification untuk trials yang akan expire
            foreach ($expiringTrials as $trial) {
                // Dispatch notification job
                // TrialExpiringSoonNotification::dispatch($trial);
                $this->line("  - Trial {$trial['id']} will expire on {$trial['expires_at']}");
            }

            Log::info('Trial expiration processing completed', [
                'expired_count' => $expiredCount,
                'expiring_soon_count' => count($expiringTrials),
            ]);

            $this->info('Trial expiration processing completed successfully');

            return self::SUCCESS;
        } catch (\Exception $e) {
            Log::error('Failed to process trial expirations: ' . $e->getMessage());
            $this->error('Failed to process trial expirations: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
