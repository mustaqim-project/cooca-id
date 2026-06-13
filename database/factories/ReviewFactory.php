<?php

namespace Database\Factories;

use App\Models\Review;
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
            'reviewable_type' => \App\Models\Product::class,
            'reviewable_id' => \App\Models\Product::inRandomOrder()->first()?->id ?? \App\Models\Product::factory(),
            'reviewer_type' => fake()->randomElement(['customer', 'affiliator']),
            'reviewer_id' => null,
            'rating' => fake()->numberBetween(1, 5),
            'title' => fake()->optional(0.8)->sentence(4),
            'comment' => fake()->paragraphs(2, true),
            'is_approved' => fake()->boolean(70),
            'approved_by' => null,
            'approved_at' => null,
        ];
    }

    /**
     * Indicate that the review is from a customer.
     */
    public function fromCustomer(): static
    {
        return $this->state(fn (array $attributes) => [
            'reviewer_type' => 'customer',
            'reviewer_id' => \App\Models\Customer::factory(),
        ]);
    }

    /**
     * Indicate that the review is from an affiliator.
     */
    public function fromAffiliator(): static
    {
        return $this->state(fn (array $attributes) => [
            'reviewer_type' => 'affiliator',
            'reviewer_id' => \App\Models\Affiliator::factory(),
        ]);
    }

    /**
     * Indicate that the review is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => true,
            'approved_by' => \App\Models\Admin::factory(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Indicate that the review is pending approval.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_approved' => false,
        ]);
    }

    /**
     * Indicate that the review has a 5-star rating.
     */
    public function fiveStars(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 5,
        ]);
    }

    /**
     * Indicate that the review has a low rating (1-2 stars).
     */
    public function lowRating(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => fake()->numberBetween(1, 2),
        ]);
    }
}
