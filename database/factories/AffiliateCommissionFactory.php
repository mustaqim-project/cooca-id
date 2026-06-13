<?php

namespace Database\Factories;

use App\Models\AffiliateCommission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AffiliateCommission>
 */
class AffiliateCommissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grossAmount = fake()->randomFloat(2, 100, 2000);
        $commissionPercent = fake()->randomFloat(2, 5, 30);
        $commissionAmount = ($grossAmount * $commissionPercent) / 100;
        
        return [
            'id' => (string) Str::uuid(),
            'affiliator_id' => \App\Models\Affiliator::inRandomOrder()->first()?->id ?? \App\Models\Affiliator::factory(),
            'transaction_id' => \App\Models\Transaction::inRandomOrder()->first()?->id ?? \App\Models\Transaction::factory(),
            'customer_id' => \App\Models\Customer::inRandomOrder()->first()?->id ?? \App\Models\Customer::factory(),
            'level' => fake()->numberBetween(1, 2),
            'gross_amount' => $grossAmount,
            'commission_percent' => $commissionPercent,
            'commission_amount' => $commissionAmount,
            'status' => fake()->randomElement(['pending', 'cleared', 'cancelled']),
            'cleared_at' => null,
        ];
    }

    /**
     * Indicate that the commission is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the commission is cleared.
     */
    public function cleared(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cleared',
            'cleared_at' => now(),
        ]);
    }

    /**
     * Indicate that the commission is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Indicate that the commission is level 1 (direct referral).
     */
    public function level1(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 1,
        ]);
    }

    /**
     * Indicate that the commission is level 2 (indirect referral).
     */
    public function level2(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 2,
        ]);
    }
}
