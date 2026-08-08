<?php

namespace Database\Factories;

use App\Models\AffiliateWithdrawal;
use App\Models\Affiliator;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AffiliateWithdrawal>
 */
class AffiliateWithdrawalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->randomElement([50000, 100000, 250000, 500000, 1000000]);

        return [
            'id' => (string) Str::uuid(),
            'affiliator_id' => Affiliator::inRandomOrder()->first()?->id ?? Affiliator::factory(),
            'amount' => $amount,
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'paid']),
            'bank_account' => fake()->bankAccountNumber(),
            'bank_name' => fake()->company(),
            'approved_by' => null,
            'approved_at' => null,
            'paid_at' => null,
        ];
    }

    /**
     * Indicate that the withdrawal is pending.
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the withdrawal is approved.
     */
    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'approved',
            'approved_by' => Admin::inRandomOrder()->first()?->id ?? Admin::factory(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Indicate that the withdrawal is paid.
     */
    public function paid(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'paid',
            'approved_by' => Admin::inRandomOrder()->first()?->id ?? Admin::factory(),
            'approved_at' => now()->subDays(5),
            'paid_at' => now(),
        ]);
    }
}
