<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use App\Models\Product;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        SubscriptionPlan::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $products = Product::all();

        foreach ($products as $product) {
            $plans = [
                [
                    'product_id' => $product->id,
                    'name' => 'Monthly Plan',
                    'duration_months' => 1,
                    'price' => $product->base_price,
                    'discount_percent' => 0,
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'product_id' => $product->id,
                    'name' => 'Quarterly Plan',
                    'duration_months' => 3,
                    'price' => $product->base_price * 2.7,
                    'discount_percent' => 10,
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'product_id' => $product->id,
                    'name' => 'Annual Plan',
                    'duration_months' => 12,
                    'price' => $product->base_price * 10,
                    'discount_percent' => 20,
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                [
                    'product_id' => $product->id,
                    'name' => 'Lifetime Plan',
                    'duration_months' => 999,
                    'price' => $product->base_price * 30, // Example multiplier for lifetime
                    'discount_percent' => 30,
                    'is_active' => true,
                    'sort_order' => 4,
                ],
            ];

            foreach ($plans as $plan) {
                SubscriptionPlan::create($plan);
            }
        }

        $this->command->info('Subscription plans seeded successfully.');
    }
}
