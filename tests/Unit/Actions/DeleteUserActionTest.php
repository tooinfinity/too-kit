<?php

declare(strict_types=1);

use App\Actions\Users\DeleteUserAction;
use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    // Create an admin user and assign the admin role
    $adminUser = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => 'admin']);
    $adminUser->assignRole($adminRole);
    $this->actingAs($adminUser);
});

test('it can delete a user', function () {
    $userToDelete = User::factory()->create([
        'name' => 'user to delete',
        'email' => 'delete@app.com',
        'password' => 'password',
    ]);

    $action = new DeleteUserAction();
    $action->handle($userToDelete);

    $userDeleted = User::query()->find($userToDelete->id);
    expect($userDeleted)->toBeNull();
});

test('it cannot delete the currently authenticated user', function () {
    // Create a user who will attempt to delete their own account
    $userToDelete = User::factory()->create([
        'name' => 'user to delete',
        'email' => 'delete@app.com',
        'password' => 'password',
    ]);

    // Simulate the authenticated user being the one to delete
    $this->actingAs($userToDelete);

    $action = new DeleteUserAction();
    $result = $action->handle($userToDelete);

    // Assuming the action returns false when trying to delete self
    expect($result)->toBeFalse();
});
