<?php

namespace Database\Factories;

use App\Models\Affiliator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Affiliator>
 */
class AffiliatorFactory extends Factory
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
            'id' => (string) Str::uuid(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'balance' => 0,
            'bank_account' => null,
            'bank_name' => null,
            'parent_affiliator_id' => null,
            'referral_code' => strtoupper(Str::random(8)),
            'google_id' => null,
        ];
    }

    /**
     * Indicate that the affiliator has a parent.
     */
    public function withParent(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_affiliator_id' => \App\Models\Affiliator::factory(),
        ]);
    }

    /**
     * Indicate that the affiliator has banking details.
     */
    public function withBankDetails(): static
    {
        return $this->state(fn (array $attributes) => [
            'bank_account' => fake()->bankAccountNumber(),
            'bank_name' => fake()->company(),
        ]);
    }
}
