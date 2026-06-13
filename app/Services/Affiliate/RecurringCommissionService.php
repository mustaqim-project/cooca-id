<?php

namespace App\Services\Affiliate;

use App\Models\Subscription;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class RecurringCommissionService
{
    protected CommissionCalculationService $calculationService;

    public function __construct(CommissionCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    /**
     * Process recurring commissions for subscription renewals
     */
    public function processRenewalCommissions(): int
    {
        $processedCount = 0;

        // Get recent successful renewal payments
        $renewalPayments = Order::where('type', 'subscription_renewal')
            ->where('status', 'paid')
            ->whereNotNull('affiliate_id')
            ->whereDoesntHave('commission', function ($query) {
                $query->where('status', '!=', 'pending');
            })
            ->latest()
            ->get();

        DB::beginTransaction();
        try {
            foreach ($renewalPayments as $order) {
                $this->calculationService->calculateForOrder($order);
                $processedCount++;
            }

            DB::commit();

            // Create activity log
            \App\Models\ActivityLog::create([
                'user_id' => null,
                'user_type' => 'system',
                'action' => 'recurring_commission_processed',
                'module' => 'Affiliate',
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
        $totalRenewals = Order::where('type', 'subscription_renewal')
            ->where('status', 'paid')
            ->whereNotNull('affiliate_id')
            ->count();

        $totalRenewalCommission = Order::where('type', 'subscription_renewal')
            ->where('status', 'paid')
            ->whereNotNull('affiliate_id')
            ->join('affiliate_commissions', 'orders.id', '=', 'affiliate_commissions.order_id')
            ->sum('affiliate_commissions.commission_amount');

        return [
            'total_renewals_with_affiliate' => $totalRenewals,
            'total_renewal_commission_paid' => $totalRenewalCommission,
        ];
    }
}
