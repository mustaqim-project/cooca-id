<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Admin>
 */
class AdminFactory extends Factory
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
            'permissions' => ['dashboard', 'users', 'products', 'orders'],
        ];
    }

    /**
     * Indicate that the admin is super admin with all permissions.
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'permissions' => ['*'],
        ]);
    }
}
