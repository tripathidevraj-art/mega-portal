<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
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
// database/factories/UserFactory.php
public function definition(): array
{
    return [
        'full_name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'phone' => fake()->phoneNumber(),
        'date_of_birth' => fake()->date(),
        'gender' => fake()->randomElement(['male', 'female', 'other']),
        'country' => fake()->country(),
        'current_address' => fake()->address(),
        'occupation' => fake()->jobTitle(),
        'company' => fake()->company(),
        'skills' => [fake()->word(), fake()->word()],
        'civil_id' => fake()->regexify('[A-Z][0-9]{8}'),
        'password' => Hash::make('password'),
        'role' => 'user',
        'status' => 'verified',
        'email_verified_at' => now(),
    ];
}

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
