<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Command untuk memproses commission holding period daily
 * Business Rule: Commission menjadi available setelah 14 hari holding period
 */
class ProcessCommissionHoldingPeriodCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commissions:process-holding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process commission holding period (14 days) and mark as available';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Processing commission holding period...');

        try {
            // Dispatch job untuk process holding period
            \App\Jobs\Commission\ProcessCommissionHoldingPeriodJob::dispatch();

            $this->info('Commission holding period job dispatched successfully');

            Log::info('Commission holding period command executed');

            return self::SUCCESS;
        } catch (\Exception $e) {
            Log::error('Failed to process commission holding period: ' . $e->getMessage());
            $this->error('Failed to process commission holding period: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
