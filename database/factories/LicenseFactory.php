<?php

namespace Database\Factories;

use App\Models\License;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<License>
 */
class LicenseFactory extends Factory
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
            'customer_id' => \App\Models\Customer::inRandomOrder()->first()?->id ?? \App\Models\Customer::factory(),
            'product_id' => \App\Models\Product::inRandomOrder()->first()?->id ?? \App\Models\Product::factory(),
            'subscription_plan_id' => \App\Models\SubscriptionPlan::inRandomOrder()->first()?->id ?? \App\Models\SubscriptionPlan::factory(),
            'license_code' => strtoupper(Str::random(16)) . '-' . strtoupper(Str::random(8)),
            'token_code' => hash('sha256', Str::random(40)),
            'domain' => fake()->optional(0.7)->domainName(),
            'status' => fake()->randomElement(['inactive', 'active', 'expired', 'revoked']),
            'activated_at' => fake()->optional(0.7)->dateTimeBetween('-1 year', 'now'),
            'expires_at' => fake()->optional(0.7)->dateTimeBetween('now', '+1 year'),
            'revoked_at' => null,
            'revoked_by' => null,
            'revocation_reason' => null,
        ];
    }

    /**
     * Indicate that the license is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'activated_at' => now(),
            'expires_at' => now()->addYear(),
        ]);
    }

    /**
     * Indicate that the license is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'activated_at' => now()->subYears(2),
            'expires_at' => now()->subMonth(),
        ]);
    }

    /**
     * Indicate that the license is revoked.
     */
    public function revoked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'revoked',
            'revoked_at' => now(),
            'revoked_by' => \App\Models\Admin::factory(),
            'revocation_reason' => fake()->sentence(),
        ]);
    }

    /**
     * Indicate that the license is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
            'activated_at' => null,
        ]);
    }
}
