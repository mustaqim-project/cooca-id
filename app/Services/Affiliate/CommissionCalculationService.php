<?php

declare(strict_types=1);

namespace App\Services\Affiliate;

use App\Models\Transaction;
use App\Models\Affiliator;
use App\Models\Customer;
use App\Models\AffiliateWallet;
use App\Models\AffiliateCommission;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

final class CommissionCalculationService
{
    /**
     * Get commission rate from database settings or config fallback
     */
    private function getCommissionRate(int $level): float
    {
        $key = $level === 1 ? 'affiliate.commission_rate_level_1' : 'affiliate.commission_rate_level_2';
        $defaultValue = $level === 1 ? 25 : 5;

        // Try to get from database settings first
        $rate = Setting::get($key);

        if ($rate === null) {
            // Fallback to config
            $rate = config('affiliate.commission_rate_level_' . $level, $defaultValue);
        }

        // Convert percentage to decimal (e.g., 25% -> 0.25)
        return (float) $rate / 100;
    }

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
            $level1Rate = $this->getCommissionRate(1);
            $level1Commission = $grossAmount * $level1Rate;

            $this->recordCommission($affiliate, $transaction, $level1Commission, 1, $level1Rate * 100);

            // Level 2 commission (upline)
            if ($affiliate->parent_affiliator_id) {
                $upline = Affiliator::findOrFail($affiliate->parent_affiliator_id);
                $level2Rate = $this->getCommissionRate(2);
                $level2Commission = $grossAmount * $level2Rate;

                $this->recordCommission($upline, $transaction, $level2Commission, 2, $level2Rate * 100);
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
    private function recordCommission(Affiliator $affiliate, Transaction $transaction, float $amount, int $level, float $percent): void
    {
        $commission = AffiliateCommission::create([
            'referred_by_id' => $affiliate->id,
            'affiliator_id' => $affiliate->id,
            'transaction_id' => $transaction->id,
            'customer_id' => $transaction->customer_id,
            'level' => $level,
            'gross_amount' => $transaction->gross_amount,
            'commission_percent' => $percent,
            'commission_amount' => $amount,
            'status' => 'pending',
            'cleared_at' => null,
        ]);

        // Add to wallet as pending
        $wallet = AffiliateWallet::firstOrCreate(
            ['referred_by_id' => $affiliate->id],
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
        $level1Rate = $this->getCommissionRate(1);
        $level2Rate = $this->getCommissionRate(2);

        return [
            'gross_amount' => $grossAmount,
            'level_1' => [
                'rate' => $level1Rate,
                'rate_percent' => $level1Rate * 100,
                'amount' => $grossAmount * $level1Rate,
            ],
            'level_2' => [
                'rate' => $level2Rate,
                'rate_percent' => $level2Rate * 100,
                'amount' => $grossAmount * $level2Rate,
            ],
            'total_commission' => ($grossAmount * $level1Rate) + ($grossAmount * $level2Rate),
        ];
    }
}
