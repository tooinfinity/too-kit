<?php

declare(strict_types=1);

use App\Actions\Users\StoreUserAction;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

beforeEach(function () {
    $adminUser = User::factory()->create();
    $adminRole = Role::factory()->create(['name' => 'admin']);
    $permission = Permission::factory()->create(['name' => 'view users']);
    $adminRole->givePermissionTo($permission);
    $adminUser->assignRole($adminRole);
    $this->actingAs($adminUser);
});

test('it can create a user', function () {
    $attributes = [
        'name' => 'Test User',
        'email' => 'test@gmail.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'roles' => 'admin',
        'permissions' => [
            'view users',
        ],
    ];
    $action = new StoreUserAction();
    $action->handle($attributes);

    $user = User::query()->where('email', $attributes['email'])->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe($attributes['name'])
        ->and($user->email)->toBe($attributes['email'])
        ->and(Hash::check($attributes['password'], $user->password))->toBeTrue()
        ->and($user->hasRole($attributes['roles']))->toBeTrue();
});
