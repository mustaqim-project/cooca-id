<?php

namespace Database\Factories;

use App\Models\EmailCampaign;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<EmailCampaign>
 */
class EmailCampaignFactory extends Factory
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
            'name' => fake()->unique()->sentence(4),
            'subject' => fake()->sentence(6),
            'content' => fake()->paragraphs(4, true),
            'recipient_type' => fake()->randomElement(['all_customers', 'segment', 'specific']),
            'segment_criteria' => ['status' => 'active', 'min_spent' => 500000],
            'total_recipients' => fake()->numberBetween(100, 5000),
            'sent_count' => fake()->numberBetween(100, 5000),
            'opened_count' => fake()->numberBetween(50, 4000),
            'clicked_count' => fake()->numberBetween(10, 2000),
            'status' => fake()->randomElement(['draft', 'scheduled', 'sending', 'completed', 'failed']),
            'scheduled_at' => fake()->optional(0.5)->dateTimeBetween('now', '+1 week'),
            'sent_at' => fake()->optional(0.6)->dateTimeBetween('-2 months', 'now'),
            'created_by' => \App\Models\Admin::inRandomOrder()->first()?->id ?? \App\Models\Admin::factory(),
        ];
    }

    /**
     * Indicate that the campaign is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the campaign is scheduled.
     */
    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
            'scheduled_at' => now()->addDays(2),
        ]);
    }

    /**
     * Indicate that the campaign is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'sent_at' => now()->subDays(5),
            'total_recipients' => fake()->numberBetween(500, 15000),
            'sent_count' => fake()->numberBetween(500, 15000),
            'opened_count' => fake()->numberBetween(200, 10000),
            'clicked_count' => fake()->numberBetween(50, 5000),
        ]);
    }

    /**
     * Indicate that the campaign failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
        ]);
    }
}
