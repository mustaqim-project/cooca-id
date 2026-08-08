<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Customer;
use App\Models\Affiliator;
use App\Models\Product;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
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
            'reviewer_id' => Customer::inRandomOrder()->first()?->id ?? Customer::factory(),
            'reviewer_type' => 'customer',
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'content' => fake()->paragraphs(2, true),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'is_approved' => fake()->boolean(70),
            'user_type' => fake()->randomElement(['customer', 'affiliator', 'admin']),
            'approved_by' => null,
            'approved_at' => null,
        ];
    }

    /**
     * Indicate that the review is from a customer.
     */
    public function fromCustomer(): static
    {
        return $this->state(fn(array $attributes) => [
            'reviewer_id' => Customer::factory(),
            'reviewer_type' => 'customer',
        ]);
    }

    /**
     * Indicate that the review is from an affiliator.
     */
    public function fromAffiliator(): static
    {
        return $this->state(fn(array $attributes) => [
            'reviewer_id' => Affiliator::factory(),
            'reviewer_type' => 'affiliator',
        ]);
    }

    /**
     * Indicate that the review is approved.
     */
    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'approved',
            'is_approved' => true,
            'approved_by' => Admin::inRandomOrder()->first()?->id ?? Admin::factory(),
            'approved_at' => now(),
        ]);
    }
}
