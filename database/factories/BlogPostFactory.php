<?php

namespace Database\Factories;

use App\Models\BlogPost;
use App\Models\Admin;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'id' => (string) Str::uuid(),
            'author_id' => Admin::inRandomOrder()->first()?->id ?? Admin::factory(),
            'category' => fake()->randomElement(['Retail', 'Restaurant', 'Hotel', 'Clinic', 'AI & Automation', 'Revenue Optimization', 'Case Studies']),
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(5, true),
            'featured_image' => fake()->optional(0.7)->imageUrl(1200, 630, 'business', true),
            'is_published' => fake()->boolean(80),
            'published_at' => fake()->optional(0.8)->dateTimeBetween('-3 months', 'now'),
        ];
    }

    /**
     * Indicate that the blog post is published.
     */
    public function published(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_published' => true,
            'published_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }
}
