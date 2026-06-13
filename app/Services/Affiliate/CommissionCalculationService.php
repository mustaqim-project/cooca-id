<?php

declare(strict_types=1);

namespace App\Services\Affiliate;

use App\Models\Transaction;
use App\Models\Affiliator;
use App\Models\AffiliateWallet;
use App\Models\AffiliateCommission;
use Illuminate\Support\Facades\DB;

final class CommissionCalculationService
{
    /**
     * Level 1 commission rate: 25%
     * Level 2 commission rate: 5%
     */
    const LEVEL_1_RATE = 0.25;
    const LEVEL_2_RATE = 0.05;

    /**
     * Calculate and record commissions for a transaction
     */
    public function calculateForTransaction(Transaction $transaction): void
    {
        if (!$transaction->customer?->affiliator_id) {
            return;
        }

        DB::beginTransaction();
        try {
            $customer = $transaction->customer;
            $affiliate = Affiliator::findOrFail($customer->affiliator_id);
            
            // Calculate based on gross revenue (before voucher discount)
            $grossAmount = $transaction->gross_amount;
            
            // Level 1 commission (direct referral)
            $level1Commission = $grossAmount * self::LEVEL_1_RATE;
            
            $this->recordCommission($affiliate, $transaction, $level1Commission, 1);

            // Level 2 commission (upline)
            if ($affiliate->parent_affiliator_id) {
                $upline = Affiliator::findOrFail($affiliate->parent_affiliator_id);
                $level2Commission = $grossAmount * self::LEVEL_2_RATE;
                
                $this->recordCommission($upline, $transaction, $level2Commission, 2);
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
    private function recordCommission(Affiliator $affiliate, Transaction $transaction, float $amount, int $level): void
    {
        $commission = AffiliateCommission::create([
            'affiliator_id' => $affiliate->id,
            'transaction_id' => $transaction->id,
            'customer_id' => $transaction->customer_id,
            'level' => $level,
            'gross_amount' => $transaction->gross_amount,
            'commission_percent' => $level === 1 ? self::LEVEL_1_RATE * 100 : self::LEVEL_2_RATE * 100,
            'commission_amount' => $amount,
            'status' => 'pending',
            'cleared_at' => null,
        ]);

        // Add to wallet as pending
        $wallet = AffiliateWallet::firstOrCreate(
            ['affiliator_id' => $affiliate->id],
            ['balance' => 0, 'pending_balance' => 0]
        );

        $wallet->increment('pending_balance', $amount);

        // Send notification
        $affiliate->notify(new \App\Notifications\Affiliator\CommissionEarnedNotification($commission));
    }

    /**
     * Get commission breakdown for a transaction
     */
    public function getBreakdown(Transaction $transaction): array
    {
        $grossAmount = $transaction->gross_amount;
        
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
