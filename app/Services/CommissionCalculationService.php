<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Setting;

class CommissionCalculationService
{
    public function calculateCommission($subscription): float
    {
        if (!$subscription->referred_by_id) {
            return 0;
        }

        $affiliator = $subscription->affiliator;
        if (!$affiliator) {
            return 0;
        }

        // Fallback to setting if affiliator doesn't have custom rate
        $commissionRate = Setting::get('affiliate_commission_rate', 10.0);
        return ($subscription->amount * $commissionRate) / 100;
    }
}
