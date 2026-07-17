<?php

declare(strict_types=1);

namespace App\Jobs\Commission;

use App\Models\AffiliateCommission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Job untuk memproses holding period commission affiliate
 * Business Rule: Commission masuk holding period 14 hari setelah invoice paid
 * Setelah 14 hari, status berubah dari 'pending' ke 'available'
 */
class ProcessCommissionHoldingPeriodJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $processedCount = 0;
        $now = now();

        // Cari commission yang sudah melewati holding period 14 hari
        // Status masih pending dan invoice_paid_at sudah lebih dari 14 hari yang lalu
        $commissionsReady = AffiliateCommission::where('status', AffiliateCommission::STATUS_PENDING)
            ->whereNotNull('invoice_paid_at')
            ->where('invoice_paid_at', '<=', now()->copy()->subDays(14))
            ->get();

        DB::transaction(function () use ($commissionsReady, &$processedCount) {
            foreach ($commissionsReady as $commission) {
                try {
                    $this->markCommissionAsAvailable($commission);
                    $processedCount++;
                } catch (\Exception $e) {
                    Log::error("Failed to process commission {$commission->id}: " . $e->getMessage());
                    // Continue processing other commissions
                }
            }
        });

        // Log summary
        Log::info("Commission holding period job completed", [
            'processed_count' => $processedCount,
            'processed_at' => $now->toIso8601String(),
        ]);

        // Create activity log
        \App\Models\ActivityLog::create([
            'causer_id' => null,
            'causer_type' => 'system',
            'action' => 'commission_holding_period_processed',
            'module' => 'affiliate',
            'description' => "Processed {$processedCount} commissions ready after 14 days holding period",
            'ip_address' => 'system',
            'user_agent' => 'Scheduler',
            'metadata' => [
                'processed_count' => $processedCount,
                'processed_at' => $now->toIso8601String(),
            ],
        ]);
    }

    /**
     * Mark commission as available after holding period
     */
    private function markCommissionAsAvailable(AffiliateCommission $commission): void
    {
        // Double check holding period
        if (!$commission->invoice_paid_at) {
            throw new \InvalidArgumentException(
                "Commission {$commission->id} has no invoice_paid_at date"
            );
        }

        $daysSincePayment = now()->diffInDays($commission->invoice_paid_at);
        
        if ($daysSincePayment < 14) {
            Log::warning(
                "Commission {$commission->id} not ready yet. Days since payment: {$daysSincePayment}"
            );
            return;
        }

        // Update status to available
        $commission->markAsAvailable();

        // Record in activity log
        \App\Models\ActivityLog::create([
            'causer_id' => $commission->affiliator_id,
            'causer_type' => \App\Models\Affiliator::class,
            'action' => 'commission_available',
            'module' => 'affiliate',
            'description' => "Commission {$commission->id} is now available for withdrawal after 14 days holding period",
            'ip_address' => 'system',
            'user_agent' => 'Scheduler',
            'metadata' => [
                'commission_id' => $commission->id,
                'affiliator_id' => $commission->affiliator_id,
                'amount' => $commission->commission_amount,
                'invoice_paid_at' => $commission->invoice_paid_at->toIso8601String(),
                'holding_days' => $daysSincePayment,
            ],
        ]);

        Log::info("Commission marked as available", [
            'commission_id' => $commission->id,
            'affiliator_id' => $commission->affiliator_id,
            'amount' => $commission->commission_amount,
            'days_in_holding' => $daysSincePayment,
        ]);
    }
}
