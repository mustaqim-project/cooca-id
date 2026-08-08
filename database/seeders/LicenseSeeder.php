<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Models\License;
use App\Models\Subscription;
use Illuminate\Support\Str;

final class LicenseSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products  = Product::with('subscriptionPlans')->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        foreach ($customers as $index => $customer) {
            $product = $products->get($index % $products->count());
            $plan    = $product->subscriptionPlans->first();

            if (!$plan) continue;

            $licenseCode = 'LIC-' . strtoupper(Str::random(12)); // 16 chars max
            $tokenCode   = 'TOK-' . strtoupper(Str::random(12)); // 16 chars max

            $license = License::firstOrCreate(
                ['domain' => $customer->domain ?? 'demo-' . $index . '.cooca.id'],
                [
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'subscription_plan_id' => $plan->id,
                    'license_code' => $licenseCode,
                    'token_code' => $tokenCode,
                    'status' => 'active',
                    'activated_at' => now()->subDays(5),
                    'expires_at' => now()->addDays(30),
                ]
            );

            Subscription::firstOrCreate(
                [
                    'customer_id' => $customer->id,
                    'subscription_plan_id' => $plan->id,
                ],
                [
                    'license_id' => $license->id,
                    'status' => 'active',
                    'started_at' => now()->subDays(5),
                    'expires_at' => now()->addDays(30),
                ]
            );
        }

        echo "✅ Active Licenses & Subscriptions successfully seeded.\n";
    }
}
