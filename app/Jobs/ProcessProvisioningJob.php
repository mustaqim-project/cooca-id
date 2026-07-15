<?php

namespace App\Jobs;

use App\Models\ProvisioningJob as ProvJob;
use App\Services\Provisioning\ProvisioningEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessProvisioningJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     * Backoff strategy: 1 min, 5 mins.
     */
    public function backoff(): array
    {
        return [60, 300];
    }

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ProvJob $provisioningJob
    ) {}

    /**
     * Execute the job.
     */
    public function handle(ProvisioningEngine $engine): void
    {
        $engine->run($this->provisioningJob);
    }
}
