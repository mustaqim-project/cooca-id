<?php

namespace Database\Factories;

use App\Models\Voucher;
use App\Models\Admin;
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
            'code' => strtoupper(Str::random(8)),
            'type' => fake()->randomElement(['percentage', 'fixed']),
            'value' => fake()->randomElement([10, 15, 20, 25, 50]),
            'max_uses' => fake()->numberBetween(10, 100),
            'used_count' => 0,
            'min_purchase' => fake()->optional()->randomElement([100000, 250000, 500000]),
            'max_discount' => null,
            'starts_at' => now(),
            'expires_at' => fake()->dateTimeBetween('+1 month', '+6 months'),
            'is_active' => true,
            'created_by' => Admin::inRandomOrder()->first()?->id ?? Admin::factory(),
        ];
    }
}
