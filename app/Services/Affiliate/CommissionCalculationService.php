<?php

namespace App\Services\Affiliate;

use App\Models\Order;
use App\Models\User;
use App\Models\AffiliateWallet;
use App\Models\AffiliateCommission;
use Illuminate\Support\Facades\DB;

class CommissionCalculationService
{
    /**
     * Level 1 commission rate: 25%
     * Level 2 commission rate: 5%
     */
    const LEVEL_1_RATE = 0.25;
    const LEVEL_2_RATE = 0.05;

    /**
     * Calculate and record commissions for an order
     */
    public function calculateForOrder(Order $order): void
    {
        if (!$order->affiliate_id) {
            return;
        }

        DB::beginTransaction();
        try {
            $affiliate = User::findOrFail($order->affiliate_id);
            
            // Calculate based on gross revenue (before voucher discount)
            $grossAmount = $order->gross_amount ?? $order->total_amount;
            
            // Level 1 commission (direct referral)
            $level1Commission = $grossAmount * self::LEVEL_1_RATE;
            
            $this->recordCommission($affiliate, $order, $level1Commission, 1);

            // Level 2 commission (upline)
            if ($affiliate->referrer_id) {
                $upline = User::findOrFail($affiliate->referrer_id);
                $level2Commission = $grossAmount * self::LEVEL_2_RATE;
                
                $this->recordCommission($upline, $order, $level2Commission, 2);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Record a commission entry
     */
    private function recordCommission(User $affiliate, Order $order, float $amount, int $level): void
    {
        $commission = AffiliateCommission::create([
            'affiliate_id' => $affiliate->id,
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'level' => $level,
            'gross_amount' => $order->gross_amount ?? $order->total_amount,
            'commission_rate' => $level === 1 ? self::LEVEL_1_RATE : self::LEVEL_2_RATE,
            'commission_amount' => $amount,
            'status' => 'pending',
            'calculated_at' => now(),
        ]);

        // Add to wallet as pending
        $wallet = AffiliateWallet::firstOrCreate(
            ['user_id' => $affiliate->id],
            ['balance' => 0, 'pending_balance' => 0]
        );

        $wallet->increment('pending_balance', $amount);

        // Send notification
        $affiliate->notify(new \App\Notifications\CommissionEarnedNotification($commission));
    }

    /**
     * Get commission breakdown for an order
     */
    public function getBreakdown(Order $order): array
    {
        $grossAmount = $order->gross_amount ?? $order->total_amount;
        
        return [
            'gross_amount' => $grossAmount,
            'level_1' => [
                'rate' => self::LEVEL_1_RATE,
                'amount' => $grossAmount * self::LEVEL_1_RATE,
            ],
            'level_2' => [
                'rate' => self::LEVEL_2_RATE,
                'amount' => $grossAmount * self::LEVEL_2_RATE,
            ],
            'total_commission' => ($grossAmount * self::LEVEL_1_RATE) + ($grossAmount * self::LEVEL_2_RATE),
        ];
    }
}
