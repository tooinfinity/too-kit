<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Permission;

/**
 * @extends Factory<Permission>
 */
final class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
        ];

        return [
            'name' => $this->faker->randomElement($permissions),
            'guard_name' => 'web',
        ];
    }
}
