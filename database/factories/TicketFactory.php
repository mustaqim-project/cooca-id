<?php

namespace Database\Factories;

use App\Models\Ticket;
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
            'ticket_number' => 'TKT-' . strtoupper(Str::random(8)),
            'customer_id' => null,
            'affiliator_id' => null,
            'admin_id' => null,
            'subject' => fake()->sentence(),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => fake()->randomElement(['open', 'in_progress', 'resolved', 'closed']),
            'category' => fake()->randomElement(['technical', 'billing', 'sales', 'general', 'complaint']),
            'resolved_at' => null,
            'closed_at' => null,
        ];
    }

    /**
     * Indicate that the ticket is open.
     */
    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
        ]);
    }

    /**
     * Indicate that the ticket is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
        ]);
    }

    /**
     * Indicate that the ticket is resolved.
     */
    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    /**
     * Indicate that the ticket is closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
            'resolved_at' => now()->subDays(5),
            'closed_at' => now(),
        ]);
    }

    /**
     * Indicate that the ticket is from a customer.
     */
    public function fromCustomer(): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => \App\Models\Customer::factory(),
        ]);
    }

    /**
     * Indicate that the ticket is from an affiliator.
     */
    public function fromAffiliator(): static
    {
        return $this->state(fn (array $attributes) => [
            'affiliator_id' => \App\Models\Affiliator::factory(),
        ]);
    }

    /**
     * Indicate that the ticket is assigned to an admin.
     */
    public function assignedToAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'admin_id' => \App\Models\Admin::factory(),
        ]);
    }
}
