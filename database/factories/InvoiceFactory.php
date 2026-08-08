<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
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
            'transaction_id' => Transaction::inRandomOrder()->first()?->id ?? Transaction::factory(),
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'amount' => fake()->randomElement([250000, 500000, 750000, 1500000, 2500000, 5000000, 7500000, 12500000]),
            'status' => fake()->randomElement(['pending', 'paid', 'overdue', 'cancelled']),
            'due_date' => fake()->dateTimeBetween('now', '+30 days'),
            'paid_at' => fake()->optional(0.7)->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
