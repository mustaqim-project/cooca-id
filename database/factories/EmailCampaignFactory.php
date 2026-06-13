<?php

namespace Database\Factories;

use App\Models\EmailCampaign;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'name' => fake()->unique()->sentence(3),
            'subject' => fake()->sentence(5),
            'content' => fake()->paragraphs(3, true),
            'recipient_type' => fake()->randomElement(['customers', 'affiliators', 'all']),
            'segment_criteria' => json_encode([fake()->word() => fake()->word()]),
            'total_recipients' => 0,
            'sent_count' => 0,
            'opened_count' => 0,
            'clicked_count' => 0,
            'status' => fake()->randomElement(['draft', 'scheduled', 'sending', 'completed', 'failed']),
            'scheduled_at' => null,
            'sent_at' => null,
            'created_by' => \App\Models\Admin::factory(),
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
            'total_recipients' => fake()->numberBetween(100, 10000),
            'sent_count' => fake()->numberBetween(100, 10000),
            'opened_count' => fake()->numberBetween(10, 5000),
            'clicked_count' => fake()->numberBetween(5, 2000),
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
