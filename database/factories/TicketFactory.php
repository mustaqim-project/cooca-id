<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\Customer;
use App\Models\Affiliator;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
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
            'customer_id' => Customer::inRandomOrder()->first()?->id ?? Customer::factory(),
            'affiliator_id' => null,
            'admin_id' => null,
            'ticket_number' => 'TKT-' . strtoupper(Str::random(8)),
            'subject' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => fake()->randomElement(['open', 'in_progress', 'resolved', 'closed']),
            'referred_by_id' => null,
        ];
    }

    /**
     * Indicate that the ticket is for a customer.
     */
    public function forCustomer(): static
    {
        return $this->state(fn(array $attributes) => [
            'customer_id' => Customer::factory(),
        ]);
    }

    /**
     * Indicate that the ticket is for an affiliator.
     */
    public function forAffiliator(): static
    {
        return $this->state(fn(array $attributes) => [
            'referred_by_id' => Affiliator::factory(),
        ]);
    }

    /**
     * Indicate that the ticket is assigned to an admin.
     */
    public function assignedToAdmin(): static
    {
        return $this->state(fn(array $attributes) => [
            'admin_id' => Admin::factory(),
            'status' => 'in_progress',
        ]);
    }

    /**
     * Indicate that the ticket is published.
     */
    public function published(): static
    {
        return $this->state(fn(array $attributes) => [
            'published_at' => now(),
        ]);
    }
}
