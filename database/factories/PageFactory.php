<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);
        
        return [
            'id' => (string) Str::uuid(),
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(5, true),
            'meta_title' => fake()->optional(0.7)->sentence(5),
            'meta_description' => fake()->optional(0.7)->sentence(15),
            'is_published' => true,
            'published_at' => now(),
            'created_by' => \App\Models\Admin::factory(),
        ];
    }

    /**
     * Indicate that the page is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the page is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
