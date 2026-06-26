<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grossAmount = fake()->randomElement([250000, 500000, 750000, 1500000, 2500000, 5000000, 7500000, 12500000]);
        $voucherDiscount = fake()->optional(0.3)->randomElement([50000, 100000, 150000, 250000]) ?? 0;
        if ($voucherDiscount >= $grossAmount) {
            $voucherDiscount = 0;
        }
        
        return [
            'id' => (string) Str::uuid(),
            'customer_id' => \App\Models\Customer::inRandomOrder()->first()?->id ?? \App\Models\Customer::factory(),
            'subscription_id' => \App\Models\Subscription::inRandomOrder()->first()?->id ?? \App\Models\Subscription::factory(),
            'type' => fake()->randomElement(['subscription_new', 'subscription_renewal']),
            'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
            'gross_amount' => $grossAmount,
            'voucher_discount' => $voucherDiscount,
            'net_amount' => $grossAmount - $voucherDiscount,
            'voucher_id' => null,
            'payment_method' => fake()->randomElement(['credit_card', 'bank_transfer', 'ewallet', 'qris']),
            'payment_gateway' => fake()->randomElement(['midtrans', 'xendit', 'stripe']),
            'midtrans_order_id' => fake()->optional(0.7)->uuid(),
            'midtrans_transaction_id' => fake()->optional(0.7)->uuid(),
            'midtrans_status' => fake()->optional(0.7)->randomElement(['pending', 'capture', 'settlement', 'deny', 'cancel']),
            'status' => fake()->randomElement(['pending', 'paid', 'failed', 'refunded']),
            'paid_at' => fake()->optional(0.7)->dateTimeBetween('-1 year', 'now'),
            'failed_at' => null,
            'refunded_at' => null,
        ];
    }

    /**
     * Indicate that the transaction is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Indicate that the transaction is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the transaction is failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'failed_at' => now(),
        ]);
    }

    /**
     * Indicate that the transaction is refunded.
     */
    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'refunded',
            'paid_at' => now()->subDays(10),
            'refunded_at' => now(),
        ]);
    }

    /**
     * Indicate that the transaction used a voucher.
     */
    public function withVoucher(): static
    {
        return $this->state(fn (array $attributes) => [
            'voucher_id' => \App\Models\Voucher::factory(),
            'voucher_discount' => fake()->randomElement([50000, 100000, 200000]),
        ]);
    }
}
