<?php

namespace Database\Factories;

use App\Models\Invoice;
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
            'transaction_id' => \App\Models\Transaction::inRandomOrder()->first()?->id ?? \App\Models\Transaction::factory(),
            'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
            'customer_id' => \App\Models\Customer::inRandomOrder()->first()?->id ?? \App\Models\Customer::factory(),
            'amount' => fake()->randomElement([250000, 500000, 750000, 1500000, 2500000, 5000000, 7500000, 12500000]),
            'status' => fake()->randomElement(['draft', 'issued', 'paid', 'overdue', 'cancelled']),
            'issued_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'due_at' => fake()->dateTimeBetween('now', '+1 month'),
            'paid_at' => fake()->optional(0.7)->dateTimeBetween('-1 year', 'now'),
            'pdf_path' => fake()->optional(0.5)->filePath(),
        ];
    }

    /**
     * Indicate that the invoice is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Indicate that the invoice is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'due_at' => now()->subDays(10),
        ]);
    }

    /**
     * Indicate that the invoice is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}
