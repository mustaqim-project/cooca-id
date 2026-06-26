<?php

namespace Database\Factories;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SubscriptionPlan>
 */
class SubscriptionPlanFactory extends Factory
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
            'product_id' => \App\Models\Product::inRandomOrder()->first()?->id ?? \App\Models\Product::factory(),
            'name' => fake()->randomElement(['Starter', 'Growth', 'Scale', 'Professional', 'Enterprise']) . ' Plan',
            'duration_months' => fake()->randomElement([1, 3, 6, 12]),
            'price' => fake()->randomElement([250000, 500000, 750000, 1500000, 2500000, 5000000]),
            'discount_percent' => fake()->randomElement([0, 10, 15, 20, 25]),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the plan is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
