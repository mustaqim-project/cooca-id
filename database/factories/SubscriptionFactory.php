<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
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
            'user_id' => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
            'license_id' => \App\Models\License::inRandomOrder()->first()?->id ?? \App\Models\License::factory(),
            'subscription_plan_id' => \App\Models\SubscriptionPlan::inRandomOrder()->first()?->id ?? \App\Models\SubscriptionPlan::factory(),
            'status' => fake()->randomElement(['trial', 'active', 'expired', 'cancelled']),
            'started_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'expires_at' => fake()->optional(0.8)->dateTimeBetween('now', '+1 year'),
            'cancelled_at' => null,
        ];
    }

    /**
     * Indicate that the subscription is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'started_at' => now()->subMonths(3),
            'expires_at' => now()->addMonths(9),
        ]);
    }

    /**
     * Indicate that the subscription is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'started_at' => now()->subYears(2),
            'expires_at' => now()->subMonth(),
        ]);
    }

    /**
     * Indicate that the subscription is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'started_at' => now()->subMonths(6),
            'cancelled_at' => now()->subMonth(),
        ]);
    }

    /**
     * Indicate that the subscription is on trial.
     */
    public function trial(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'trial',
            'started_at' => now()->subDays(7),
            'expires_at' => now()->addDays(7),
        ]);
    }
}
