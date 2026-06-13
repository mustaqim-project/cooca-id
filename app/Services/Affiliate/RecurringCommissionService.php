<?php

declare(strict_types=1);

namespace App\Services\Affiliate;

use App\Models\Subscription;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

final class RecurringCommissionService
{
    public function __construct(
        private readonly CommissionCalculationService $calculationService,
    ) {}

    /**
     * Process recurring commissions for subscription renewals
     */
    public function processRenewalCommissions(): int
    {
        $processedCount = 0;

        // Get recent successful renewal payments
        $renewalTransactions = Transaction::where('type', 'subscription_renewal')
            ->where('status', 'paid')
            ->whereHas('customer', function ($query) {
                $query->whereNotNull('affiliator_id');
            })
            ->whereDoesntHave('commissions', function ($query) {
                $query->where('status', '!=', 'pending');
            })
            ->latest()
            ->get();

        DB::beginTransaction();
        try {
            foreach ($renewalTransactions as $transaction) {
                $this->calculationService->calculateForTransaction($transaction);
                $processedCount++;
            }

            DB::commit();

            // Create activity log
            \App\Models\ActivityLog::create([
                'causer_id' => null,
                'causer_type' => 'system',
                'action' => 'recurring_commission_processed',
                'module' => 'affiliate',
                'description' => "Processed {$processedCount} recurring commissions",
                'ip_address' => 'system',
                'user_agent' => 'Scheduler',
                'metadata' => [
                    'processed_count' => $processedCount,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $processedCount;
    }

    /**
     * Get recurring commission statistics
     */
    public function getStatistics(): array
    {
        $totalRenewals = Transaction::where('type', 'subscription_renewal')
            ->where('status', 'paid')
            ->whereHas('customer', function ($query) {
                $query->whereNotNull('affiliator_id');
            })
            ->count();

        $totalRenewalCommission = Transaction::where('type', 'subscription_renewal')
            ->where('status', 'paid')
            ->whereHas('customer', function ($query) {
                $query->whereNotNull('affiliator_id');
            })
            ->join('affiliate_commissions', 'transactions.id', '=', 'affiliate_commissions.transaction_id')
            ->sum('affiliate_commissions.commission_amount');

        return [
            'total_renewals_with_affiliate' => $totalRenewals,
            'total_renewal_commission_paid' => $totalRenewalCommission,
        ];
    }
}
