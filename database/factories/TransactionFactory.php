<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Subscription;
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
        return [
            'id' => (string) Str::uuid(),
            'customer_id' => Customer::inRandomOrder()->first()?->id ?? Customer::factory(),
            'subscription_id' => Subscription::inRandomOrder()->first()?->id ?? Subscription::factory(),
            'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
            'type' => fake()->randomElement(['subscription_new', 'subscription_renewal', 'upgrade']),
            'status' => fake()->randomElement(['pending', 'paid', 'failed', 'refunded']),
            'gross_amount' => fake()->randomElement([500000, 750000, 1000000, 1500000, 2500000]),
            'voucher_discount' => 0,
            'net_amount' => fake()->randomElement([500000, 750000, 1000000, 1500000, 2500000]),
            'payment_method' => fake()->randomElement(['bank_transfer', 'credit_card', 'e_wallet']),
            'payment_gateway' => 'midtrans',
            'midtrans_order_id' => 'ORDER-' . strtoupper(Str::random(8)),
            'midtrans_transaction_id' => fake()->optional()->numerify('##########'),
            'midtrans_status' => fake()->optional()->randomElement(['capture', 'settlement']),
            'paid_at' => fake()->optional(0.7)->dateTimeBetween('-3 months', 'now'),
            'failed_at' => null,
            'refunded_at' => null,
        ];
    }

    /**
     * Indicate that the transaction is paid.
     */
    public function paid(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }
}
