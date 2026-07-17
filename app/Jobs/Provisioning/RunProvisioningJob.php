<?php

declare(strict_types=1);

namespace App\Jobs\Provisioning;

use App\Models\ProvisioningJob as ProvJob;
use App\Services\Provisioning\ProvisioningService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job untuk menjalankan provisioning process
 */
class RunProvisioningJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300; // 5 minutes timeout
    public int $tries = 1; // No retry at job level, handled by ProvisioningService

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly ProvJob $provisioningJob
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ProvisioningService $provisioningService): void
    {
        Log::info("RunProvisioningJob: Starting job {$this->provisioningJob->id}");

        try {
            $provisioningService->runProvisioning($this->provisioningJob);
            
            Log::info("RunProvisioningJob: Job {$this->provisioningJob->id} completed successfully");
        } catch (\Exception $e) {
            Log::error("RunProvisioningJob: Job {$this->provisioningJob->id} failed", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Don't release - errors are handled within ProvisioningService
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical("RunProvisioningJob: Job {$this->provisioningJob->id} failed permanently", [
            'error' => $exception->getMessage(),
        ]);

        // Update job status to failed
        $this->provisioningJob->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);
    }
}
