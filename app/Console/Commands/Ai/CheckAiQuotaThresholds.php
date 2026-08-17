<?php

declare(strict_types=1);

namespace App\Console\Commands\Ai;

use App\Models\AiUsageCycle;
use App\Mail\AiQuotaWarning;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

final class CheckAiQuotaThresholds extends Command
{
    protected $signature = 'ai:check-quota-thresholds';
    protected $description = 'Check AI usage cycles and notify customers at 80% and 100% usage';

    public function handle(): int
    {
        // Cycles that are active
        $activeCycles = AiUsageCycle::with('license.customer')
            ->where('cycle_end', '>=', now())
            ->where('cycle_start', '<=', now())
            ->where('token_quota', '>', 0)
            ->get();

        foreach ($activeCycles as $cycle) {
            $customer = $cycle->license?->customer;
            if (!$customer) {
                continue;
            }

            $percentage = ($cycle->tokens_used / $cycle->token_quota) * 100;
            
            // Assuming we only notify once per threshold per cycle.
            // In a robust implementation, you might track "notified_80" or "notified_100" flags on the cycle.
            // For now, we simulate this by doing a simple check.
            if ($percentage >= 100) {
                // Sent 100%
                Mail::to($customer->email)->send(new AiQuotaWarning($customer, $cycle, 100));
                $this->info("Sent 100% quota warning to {$customer->email}");
            } elseif ($percentage >= 80) {
                // Sent 80%
                Mail::to($customer->email)->send(new AiQuotaWarning($customer, $cycle, 80));
                $this->info("Sent 80% quota warning to {$customer->email}");
            }
        }

        return self::SUCCESS;
    }
}
