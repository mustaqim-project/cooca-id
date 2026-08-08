<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\SubscriptionPlan;

final class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            $basePrice = (float) $product->base_price;

            $plans = [
                [
                    'name' => 'Starter Pack (Bulanan)',
                    'duration_months' => 1,
                    'price' => $basePrice,
                    'discount_percent' => 0.00,
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'name' => 'Business Pro (Tahunan)',
                    'duration_months' => 12,
                    'price' => $basePrice * 10, // Hemat 2 bulan
                    'discount_percent' => 16.67,
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'name' => 'Enterprise Unlimited (2 Tahun)',
                    'duration_months' => 24,
                    'price' => $basePrice * 18,
                    'discount_percent' => 25.00,
                    'is_active' => true,
                    'sort_order' => 3,
                ],
            ];

            foreach ($plans as $plan) {
                SubscriptionPlan::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'name' => $plan['name'],
                    ],
                    array_merge($plan, ['product_id' => $product->id])
                );
            }
        }

        echo "✅ Subscription Plans successfully seeded.\n";
    }
}
