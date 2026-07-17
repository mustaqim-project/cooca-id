<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ErpRequest;
use Exception;

class TrialEligibilityService
{
    public function checkEligibility(Customer $customer, Product $product): bool
    {
        if (!$customer->companyProfile) {
            throw new Exception('Trial profile incomplete');
        }

        $existingTrial = ErpRequest::where('customer_id', $customer->id)
            ->where('product_id', $product->id)
            ->whereNotIn('status', ['rejected', 'trial_expired'])
            ->exists();

        if ($existingTrial) {
            throw new Exception('Trial quota exceeded');
        }

        return true;
    }
}
