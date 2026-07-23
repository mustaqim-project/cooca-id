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
        $type = fake()->randomElement(['percent', 'nominal']);
        $value = $type === 'percent' ? fake()->randomElement([10, 15, 20, 25, 30]) : fake()->randomElement([50000, 100000, 150000, 250000]);
        
        return [
            'id' => (string) Str::uuid(),
            'code' => strtoupper(fake()->unique()->lexify('COOCA-???-###')),
            'name' => fake()->sentence(3),
            'description' => fake()->optional(0.7)->paragraph(),
            'type' => $type,
            'value' => $value,
            'min_purchase' => fake()->randomElement([250000, 500000, 1000000]),
            'max_discount' => $type === 'percent' ? fake()->randomElement([100000, 250000, 500000]) : null,
            'max_usage' => fake()->optional(0.7)->numberBetween(50, 1000),
            'used_count' => fake()->numberBetween(0, 40),
            'per_user_limit' => fake()->numberBetween(1, 5),
            'valid_from' => now()->subDays(10),
            'valid_until' => now()->addDays(30),
            'is_active' => true,
            'applicable_products' => null,
            'created_by' => \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory(),
        ];
    }

    /**
     * Indicate that the voucher is a percentage discount.
     */
    public function percent(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'percent',
            'value' => fake()->randomElement([10, 15, 20, 25, 30]),
            'max_discount' => fake()->randomElement([100000, 250000, 500000]),
        ]);
    }

    /**
     * Indicate that the voucher is a nominal discount.
     */
    public function nominal(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'nominal',
            'value' => fake()->randomElement([50000, 100000, 150000, 250000]),
            'max_discount' => null,
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
