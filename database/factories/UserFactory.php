<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    private static ?string $password = null;

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
            'email_verified_at' => now(),
            'password' => self::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model should be an administrator.
     */
    public function administrator(): self
    {
        return $this->afterCreating(fn (User $user) => $user->assignRole(RoleEnum::ADMIN->value));
    }

    /**
     * Indicate that the model should be a manager.
     */
    public function manager(): self
    {
        return $this->afterCreating(fn (User $user) => $user->assignRole(RoleEnum::MANAGER->value));
    }

    /**
     * Indicate that the model should be a cashier.
     */
    public function cashier(): self
    {
        return $this->afterCreating(fn (User $user) => $user->assignRole(RoleEnum::CASHIER->value));
    }
}
