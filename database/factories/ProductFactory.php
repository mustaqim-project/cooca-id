<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        
        return [
            'id' => (string) Str::uuid(),
            'category_id' => \App\Models\ProductCategory::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(),
            'base_price' => fake()->randomFloat(2, 10, 500),
            'features' => json_encode([fake()->word(), fake()->word(), fake()->word()]),
            'specifications' => json_encode([fake()->word() => fake()->word(), fake()->word() => fake()->word()]),
            'demo_url' => fake()->optional(0.7)->url(),
            'thumbnail' => fake()->optional(0.8)->imageUrl(640, 480, 'product', true),
            'screenshots' => json_encode([]),
            'is_active' => true,
            'is_featured' => fake()->boolean(20),
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
