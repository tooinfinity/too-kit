<?php

declare(strict_types=1);

use App\Actions\Users\UpdateUserAction;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    // refresh the database

    // Create an admin user and assign the admin role
    $adminUser = User::factory()->create([
        'name' => 'admin',
        'email' => 'admin@app.com',
        'password' => Hash::make('password'),
    ]);
    $adminRole = Role::factory()->create(['name' => 'admin']);
    $permission = Permission::factory()->create(['name' => 'view users']);
    $adminRole->givePermissionTo($permission);
    $adminUser->assignRole($adminRole);
    $this->actingAs($adminUser);
});

test('it can update a user', function () {

    $userToUpdate = User::factory()->create()->fresh();
    $attributes = [
        'name' => 'updated user',
        'email' => 'updated@app.com',
        'password' => 'password',
        'roles' => ['admin'],
        'permissions' => ['view users'],
    ];
    $action = new UpdateUserAction();
    $userUpdated = $action->handle($attributes, $userToUpdate);

    // Fetch the updated user

    // Assertions to verify the user was updated correctly
    expect($userUpdated)->not->toBeNull()
        ->and($userUpdated->name)->toBe($attributes['name'])
        ->and($userUpdated->email)->toBe($attributes['email'])
        ->and(Hash::check('password', $userUpdated->password))->toBeTrue()
        ->and($userUpdated->hasRole('admin'))->toBeTrue()
        ->and($userUpdated->hasPermissionTo('view users'))->toBeTrue();
});
