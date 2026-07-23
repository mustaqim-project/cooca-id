<?php

namespace App\Services\Affiliate;

use App\Models\AffiliateCommission;
use App\Models\AffiliateWallet;
use Illuminate\Support\Facades\DB;

class SettlementService
{
    /**
     * Settle pending commissions (move from pending to available balance)
     * Run this daily or on a schedule
     */
    public function settlePendingCommissions(): int
    {
        $settledCount = 0;

        // Get all pending commissions that are older than the settlement period (e.g., 7 days)
        $pendingCommissions = AffiliateCommission::where('status', 'pending')
            ->where('created_at', '<=', now()->subDays(7))
            ->get();

        DB::beginTransaction();
        try {
            foreach ($pendingCommissions as $commission) {
                // Update commission status
                $commission->update([
                    'status' => 'cleared',
                    'cleared_at' => now(),
                ]);

                // Move from pending_balance to balance in wallet
                $wallet = AffiliateWallet::firstOrCreate(
                    ['referred_by_id' => $commission->referred_by_id],
                    ['balance' => 0, 'pending_balance' => 0]
                );
                
                $wallet->decrement('pending_balance', $commission->commission_amount);
                $wallet->increment('balance', $commission->commission_amount);

                $settledCount++;
            }

            DB::commit();

            // Create activity log
            \App\Models\ActivityLog::create([
                'user_id' => null,
                'user_type' => 'system',
                'action' => 'commission_settlement_run',
                'module' => 'Affiliate',
                'description' => "Settled {$settledCount} pending commissions",
                'ip_address' => 'system',
                'user_agent' => 'Scheduler',
                'metadata' => [
                    'settled_count' => $settledCount,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $settledCount;
    }

    /**
     * Get settlement summary
     */
    public function getSettlementSummary(): array
    {
        $pendingCount = AffiliateCommission::where('status', 'pending')->count();
        $pendingAmount = AffiliateCommission::where('status', 'pending')->sum('commission_amount');
        
        $readyToSettle = AffiliateCommission::where('status', 'pending')
            ->where('created_at', '<=', now()->subDays(7))
            ->count();
        
        $readyToSettleAmount = AffiliateCommission::where('status', 'pending')
            ->where('created_at', '<=', now()->subDays(7))
            ->sum('commission_amount');

        return [
            'total_pending_count' => $pendingCount,
            'total_pending_amount' => $pendingAmount,
            'ready_to_settle_count' => $readyToSettle,
            'ready_to_settle_amount' => $readyToSettleAmount,
        ];
    }
}
