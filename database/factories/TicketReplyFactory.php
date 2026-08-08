<?php

namespace Database\Factories;

use App\Models\TicketReply;
use App\Models\Ticket;
use App\Models\Customer;
use App\Models\Affiliator;
use App\Models\Admin;
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
            'ticket_id' => Ticket::inRandomOrder()->first()?->id ?? Ticket::factory(),
            'user_id' => Customer::inRandomOrder()->first()?->id ?? Customer::factory(),
            'user_type' => 'customer',
            'message' => fake()->paragraphs(2, true),
        ];
    }

    /**
     * Indicate that the reply is from a customer.
     */
    public function fromCustomer(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => Customer::factory(),
            'user_type' => 'customer',
        ]);
    }

    /**
     * Indicate that the reply is from an affiliator.
     */
    public function fromAffiliator(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => Affiliator::factory(),
            'user_type' => 'affiliator',
        ]);
    }

    /**
     * Indicate that the reply is from an admin.
     */
    public function fromAdmin(): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => Admin::factory(),
            'user_type' => 'admin',
        ]);
    }
}
