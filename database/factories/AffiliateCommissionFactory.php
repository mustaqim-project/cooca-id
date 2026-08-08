<?php

namespace Database\Factories;

use App\Models\AffiliateCommission;
use App\Models\Affiliator;
use App\Models\Transaction;
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
        $grossAmount = fake()->randomElement([500000, 750000, 1500000, 2500000, 5000000, 12500000]);
        $commissionPercent = fake()->randomElement([10, 15, 20, 25]);
        $commissionAmount = ($grossAmount * $commissionPercent) / 100;

        return [
            'id' => (string) Str::uuid(),
            'referred_by_id' => Affiliator::inRandomOrder()->first()?->id ?? Affiliator::factory(),
            'transaction_id' => Transaction::inRandomOrder()->first()?->id ?? Transaction::factory(),
            'affiliator_id' => Affiliator::inRandomOrder()->first()?->id ?? Affiliator::factory(),
            'level' => fake()->numberBetween(1, 2),
            'gross_amount' => $grossAmount,
            'commission_percent' => $commissionPercent,
            'commission_amount' => $commissionAmount,
            'status' => fake()->randomElement(['pending', 'cleared', 'cancelled']),
            'cleared_at' => fake()->optional(0.7)->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the commission is pending.
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the commission is cleared.
     */
    public function cleared(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'cleared',
            'cleared_at' => now(),
        ]);
    }

    /**
     * Indicate that the commission is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Indicate that the commission is level 1 (direct referral).
     */
    public function level1(): static
    {
        return $this->state(fn(array $attributes) => [
            'level' => 1,
        ]);
    }

    /**
     * Indicate that the commission is level 2 (indirect referral).
     */
    public function level2(): static
    {
        return $this->state(fn(array $attributes) => [
            'level' => 2,
        ]);
    }
}
