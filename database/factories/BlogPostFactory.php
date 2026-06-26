<?php

namespace Database\Factories;

use App\Models\BlogPost;
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
        $title = fake()->unique()->sentence(6);
        
        return [
            'id' => (string) Str::uuid(),
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'excerpt' => fake()->sentence(20),
            'content' => fake()->paragraphs(6, true),
            'featured_image' => fake()->optional(0.7)->imageUrl(1200, 630, 'business', true),
            'author_id' => \App\Models\Admin::inRandomOrder()->first()?->id ?? \App\Models\Admin::factory(),
            'category' => fake()->randomElement(['Retail', 'Restaurant', 'Hotel', 'Clinic', 'AI & Automation', 'Revenue Optimization', 'Case Studies']),
            'tags' => ['SaaS', 'Business', 'Indonesia', 'Operations', 'Growth'],
            'is_published' => fake()->boolean(85),
            'published_at' => fake()->optional(0.85)->dateTimeBetween('-6 months', 'now'),
            'views_count' => fake()->numberBetween(100, 15000),
        ];
    }

    /**
     * Indicate that the blog post is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => now()->subDays(3),
        ]);
    }

    /**
     * Indicate that the blog post is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the blog post has high views.
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'views_count' => fake()->numberBetween(5000, 25000),
        ]);
    }
}
