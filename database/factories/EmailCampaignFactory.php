<?php

namespace Database\Factories;

use App\Models\EmailCampaign;
use App\Models\Admin;
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
            'created_by' => Admin::inRandomOrder()->first()?->id ?? Admin::factory(),
            'name' => fake()->words(3, true),
            'subject' => fake()->sentence(),
            'content' => fake()->paragraphs(3, true),
            'status' => fake()->randomElement(['draft', 'scheduled', 'sent']),
            'sent_at' => null,
        ];
    }
}
