<?php

namespace Database\Factories;

use App\Models\AffiliateWithdrawal;
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
        $amount = fake()->randomElement([1000000, 2500000, 5000000, 10000000, 25000000]);
        $fee = $amount * 0.02; // 2% fee
        
        return [
            'id' => (string) Str::uuid(),
            'user_id' => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
            'amount' => $amount,
            'fee' => $fee,
            'net_amount' => $amount - $fee,
            'withdrawal_method' => fake()->randomElement(['bank', 'ewallet']),
            'account_number' => fake()->bankAccountNumber(),
            'account_name' => fake()->name(),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'paid']),
            'approved_by' => \App\Models\User::inRandomOrder()->first()?->id ?? null,
            'approved_at' => fake()->optional(0.7)->dateTimeBetween('-6 months', 'now'),
            'rejected_at' => null,
            'rejection_reason' => null,
            'paid_at' => fake()->optional(0.7)->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the withdrawal is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the withdrawal is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_by' => \App\Models\User::factory(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Indicate that the withdrawal is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => fake()->sentence(),
        ]);
    }

    /**
     * Indicate that the withdrawal is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
            'approved_by' => \App\Models\User::factory(),
            'approved_at' => now()->subDays(5),
            'paid_at' => now(),
        ]);
    }

    /**
     * Indicate that the withdrawal is via bank transfer.
     */
    public function bankTransfer(): static
    {
        return $this->state(fn (array $attributes) => [
            'withdrawal_method' => 'bank',
        ]);
    }

    /**
     * Indicate that the withdrawal is via e-wallet.
     */
    public function ewallet(): static
    {
        return $this->state(fn (array $attributes) => [
            'withdrawal_method' => 'ewallet',
        ]);
    }
}
