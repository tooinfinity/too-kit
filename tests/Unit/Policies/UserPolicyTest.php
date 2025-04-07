<?php

declare(strict_types=1);

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Policies\UserPolicy;

beforeEach(function () {
    $this->adminUser = User::factory()->create();
    $this->role = Role::factory()->create([
        'name' => 'admin',
    ]);
    $this->adminUser->assignRole($this->role);
    $viewPermission = Permission::factory()->create([
        'name' => 'view users',
    ]);
    $createPermission = Permission::factory()->create([
        'name' => 'create users',
    ]);
    $editPermission = Permission::factory()->create([
        'name' => 'edit users',
    ]);
    $deletePermission = Permission::factory()->create([
        'name' => 'delete users',
    ]);
    $this->adminUser->givePermissionTo([
        $viewPermission,
        $createPermission,
        $editPermission,
        $deletePermission,
    ])->assignRole($this->role);

    $this->policy = new UserPolicy();
});

test('user can view any user', function () {

    $this->actingAs($this->adminUser);

    $this->assertTrue($this->adminUser->can('viewAny', User::class));
});

test('user can create any user', function () {
    $this->actingAs($this->adminUser);

    $this->assertTrue($this->adminUser->can('create', User::class));
});

test('user can update any user', function () {
    $this->actingAs($this->adminUser);

    $this->assertTrue($this->adminUser->can('update', User::class));
});

test('user can delete any user', function () {
    $this->actingAs($this->adminUser);

    $this->assertTrue($this->adminUser->can('delete', User::class));
});

test('user cannot view any user', function () {
    $cashierRole = Role::factory()->create([
        'name' => 'cashier',
    ]);
    $user = User::factory()->cashier()->create();
    $this->actingAs($user);

    $this->assertFalse($user->can('view', User::class));
});

test('user cannot create any user', function () {
    Role::factory()->create([
        'name' => 'cashier',
    ]);
    $user = User::factory()->cashier()->create();
    $this->actingAs($user);

    $this->assertFalse($user->can('create', User::class));
});

test('user cannot update any user', function () {
    Role::factory()->create([
        'name' => 'cashier',
    ]);
    $user = User::factory()->cashier()->create();
    $this->actingAs($user);

    $this->assertFalse($user->can('update', User::class));
});

test('user cannot delete any user', function () {
    Role::factory()->create([
        'name' => 'cashier',
    ]);
    $user = User::factory()->cashier()->create();
    $this->actingAs($user);

    $this->assertFalse($user->can('delete', User::class));
});

test('allows an admin to bypass all checks', function () {
    expect($this->policy->viewAny($this->adminUser))->toBeTrue()
        ->and($this->policy->create($this->adminUser))->toBeTrue()
        ->and($this->policy->update($this->adminUser))->toBeTrue()
        ->and($this->policy->delete($this->adminUser))->toBeTrue();
});

test('restricts a non-admin without permissions', function () {
    $user = User::factory()->create();

    expect($this->policy->viewAny($user))->toBeFalse()
        ->and($this->policy->create($user))->toBeFalse()
        ->and($this->policy->update($user))->toBeFalse()
        ->and($this->policy->delete($user))->toBeFalse();
});

test('admin can view all users', function () {
    $user = User::factory()->create();
    $this->actingAs($this->adminUser);

    $this->assertTrue($this->adminUser->can('viewAny', $user));
});
