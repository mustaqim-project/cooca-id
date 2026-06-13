<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'business_name' => fake()->company(),
            'domain' => fake()->domainName(),
            'affiliator_id' => null,
            'google_id' => null,
        ];
    }

    /**
     * Indicate that the customer has an affiliator.
     */
    public function withAffiliator(): static
    {
        return $this->state(fn (array $attributes) => [
            'affiliator_id' => \App\Models\Affiliator::factory(),
        ]);
    }
}
