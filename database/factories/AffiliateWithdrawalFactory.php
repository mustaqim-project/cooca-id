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
        $amount = fake()->randomFloat(2, 100, 5000);
        $fee = $amount * 0.02; // 2% fee
        
        return [
            'id' => (string) Str::uuid(),
            'affiliator_id' => \App\Models\Affiliator::inRandomOrder()->first()?->id ?? \App\Models\Affiliator::factory(),
            'amount' => $amount,
            'fee' => $fee,
            'net_amount' => $amount - $fee,
            'withdrawal_method' => fake()->randomElement(['bank', 'ewallet']),
            'account_number' => fake()->bankAccountNumber(),
            'account_name' => fake()->name(),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'paid']),
            'approved_by' => null,
            'approved_at' => null,
            'rejected_at' => null,
            'rejection_reason' => null,
            'paid_at' => null,
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
            'approved_by' => \App\Models\Admin::factory(),
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
            'approved_by' => \App\Models\Admin::factory(),
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
