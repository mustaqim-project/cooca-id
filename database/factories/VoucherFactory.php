<?php

namespace Database\Factories;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Voucher>
 */
class VoucherFactory extends Factory
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
            'code' => strtoupper(fake()->unique()->lexify('VOUCHER-???-###')),
            'name' => fake()->sentence(3),
            'description' => fake()->optional(0.7)->paragraph(),
            'type' => fake()->randomElement(['percent', 'nominal']),
            'value' => fake()->randomFloat(2, 5, 50),
            'min_purchase' => fake()->randomFloat(2, 50, 500),
            'max_discount' => fake()->optional(0.5)->randomFloat(2, 50, 200),
            'max_usage' => fake()->optional(0.7)->numberBetween(10, 1000),
            'used_count' => 0,
            'per_user_limit' => fake()->optional(0.5)->numberBetween(1, 5),
            'valid_from' => now()->subDays(10),
            'valid_until' => now()->addDays(30),
            'is_active' => true,
            'applicable_products' => null,
            'created_by' => \App\Models\Admin::factory(),
        ];
    }

    /**
     * Indicate that the voucher is a percentage discount.
     */
    public function percent(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'percent',
            'value' => fake()->randomFloat(2, 5, 50),
        ]);
    }

    /**
     * Indicate that the voucher is a nominal discount.
     */
    public function nominal(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'nominal',
            'value' => fake()->randomFloat(2, 10000, 500000),
        ]);
    }

    /**
     * Indicate that the voucher is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_until' => now()->subDays(10),
        ]);
    }

    /**
     * Indicate that the voucher is not yet active.
     */
    public function notStarted(): static
    {
        return $this->state(fn (array $attributes) => [
            'valid_from' => now()->addDays(10),
        ]);
    }

    /**
     * Indicate that the voucher is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
