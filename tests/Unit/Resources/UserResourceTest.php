<?php

declare(strict_types=1);

use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;

test('auth user resource correctly without relations', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'test@app.com',
    ]);
    $resource = (new UserResource($user))->toArray(request());
    expect($resource)->toMatchArray([
        'name' => 'John Doe',
        'email' => 'test@app.com',
    ]);
});

test('transforms user resource correctly with loaded roles and permissions', function () {
    $user = User::factory()->create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $role = Role::factory()->create(['name' => 'admin']);
    $permission = Permission::factory()->create(['name' => 'edit articles']);

    $user->assignRole('admin');
    $user->givePermissionTo('edit articles');

    $user->load(['roles', 'permissions']); // Make sure they're eager loaded

    $resource = (new UserResource($user))->toArray(request());

    expect($resource)->toMatchArray([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'roles' => ['admin'],
        'permissions' => ['edit articles'],
    ]);
});
