<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\Customer;
use App\Models\License;
use App\Models\SubscriptionPlan;
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
            'customer_id' => Customer::inRandomOrder()->first()?->id ?? Customer::factory(),
            'license_id' => License::inRandomOrder()->first()?->id ?? License::factory(),
            'subscription_plan_id' => SubscriptionPlan::inRandomOrder()->first()?->id ?? SubscriptionPlan::factory(),
            'status' => fake()->randomElement(['trial', 'active', 'expired', 'cancelled']),
            'started_at' => fake()->optional(0.8)->dateTimeBetween('-6 months', 'now'),
            'expires_at' => fake()->optional(0.8)->dateTimeBetween('now', '+6 months'),
            'cancelled_at' => null,
        ];
    }

    /**
     * Indicate that the subscription is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'active',
            'started_at' => now()->subMonth(),
            'expires_at' => now()->addYear(),
        ]);
    }

    /**
     * Indicate that the subscription is a trial.
     */
    public function trial(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'trial',
            'started_at' => now(),
            'expires_at' => now()->addDays(14),
        ]);
    }
}
