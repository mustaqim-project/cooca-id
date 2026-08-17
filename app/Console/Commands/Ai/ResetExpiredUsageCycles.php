<?php

declare(strict_types=1);

namespace App\Console\Commands\Ai;

use App\Models\AiUsageCycle;
use App\Models\License;
use App\Services\Ai\AiQuotaService;
use Illuminate\Console\Command;

final class ResetExpiredUsageCycles extends Command
{
    protected $signature = 'ai:reset-usage-cycles';
    protected $description = 'Create the next AiUsageCycle for licenses whose current cycle has ended';

    public function handle(AiQuotaService $quota): int
    {
        $expiredCycles = AiUsageCycle::where('cycle_end', '<', now())
            ->whereDoesntHave('license.usageCycles', fn ($q) => $q->where('cycle_start', '>', now()))
            ->with('license')
            ->get();

        foreach ($expiredCycles as $cycle) {
            if (!$cycle->license || $cycle->license->status !== License::STATUS_ACTIVE) {
                continue;
            }

            $quota->startNewCycle($cycle->license);
            $this->info("New cycle created for license {$cycle->license_id}");
        }

        return self::SUCCESS;
    }
}
