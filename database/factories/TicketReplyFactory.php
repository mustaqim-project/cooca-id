<?php

namespace Database\Factories;

use App\Models\TicketReply;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TicketReply>
 */
class TicketReplyFactory extends Factory
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
            'ticket_id' => \App\Models\Ticket::inRandomOrder()->first()?->id ?? \App\Models\Ticket::factory(),
            'user_type' => fake()->randomElement(['customer', 'affiliator', 'admin']),
            'user_id' => \App\Models\Customer::inRandomOrder()->first()?->id ?? \App\Models\Customer::factory(),
            'message' => fake()->paragraphs(2, true),
            'is_internal' => false,
        ];
    }

    /**
     * Indicate that the reply is from a customer.
     */
    public function fromCustomer(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'customer',
            'user_id' => \App\Models\Customer::factory(),
        ]);
    }

    /**
     * Indicate that the reply is from an affiliator.
     */
    public function fromAffiliator(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'affiliator',
            'user_id' => \App\Models\Affiliator::factory(),
        ]);
    }

    /**
     * Indicate that the reply is from an admin.
     */
    public function fromAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'admin',
            'user_id' => \App\Models\Admin::factory(),
        ]);
    }

    /**
     * Indicate that the reply is internal (admin only).
     */
    public function internal(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_internal' => true,
            'user_type' => 'admin',
            'user_id' => \App\Models\Admin::factory(),
        ]);
    }
}
