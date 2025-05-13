<?php

declare(strict_types=1);

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->adminUser = User::factory()->create();
    $this->adminRole = Role::factory()->create(['name' => 'admin']);
    $this->permission = Permission::factory()->create(['name' => 'view users']);
    $this->adminUser->assignRole($this->adminRole);
    $this->actingAs($this->adminUser);
});

test('users index page can be rendered', function () {
    $response = $this->get(route('users.index'));
    $response->assertStatus(200);
});

test('users create page can be rendered', function () {
    $response = $this->get(route('users.create'));
    $response->assertStatus(200);
});

test('users edit page can be rendered', function () {
    $otherUser = User::factory()->create();
    $response = $this->get(route('users.edit', ['user' => $otherUser]));
    $response->assertStatus(200);
});

test('index page can be filtered by name', function () {
    $user = User::factory()->create(['name' => 'John Doe']);
    $response = $this->get(route('users.index', ['search' => 'John']));
    $response->assertSee($user->name);
});

test('index page can be sorted descending by name', function () {
    $user1 = User::factory()->create(['name' => 'Alice']);
    $user2 = User::factory()->create(['name' => 'Bob']);
    $response = $this->get(route('users.index', ['sort' => '-name']));
    $response->assertSeeInOrder([$user2->name, $user1->name]);
});

test('admin can store a new user', function () {

    $response = $this->post(route('users.store'), [
        'name' => 'new user',
        'email' => 'new_email@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'roles' => ['admin'],
    ]);
    $response->assertRedirect(route('users.index'));
    $user = User::query()->where('email', 'new_email@example.com')->first();
    expect($user)->not()->toBeNull()
        ->and($user->name)->toBe('new user')
        ->and($user->email)->toBe('new_email@example.com')
        ->and(Hash::check('password', $user->password))->toBeTrue()
        ->and($user->hasRole(['admin']))->toBeTrue();
});

test('admin can update a user', function () {
    $user = User::factory()->create();

    $response = $this->put(route('users.update', $user->id), [
        'name' => 'updated user',
        'email' => 'updated_email@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'roles' => ['admin'],
    ]);
    $response->assertRedirect(route('users.index'));
    $user->refresh();
    expect($user)->not()->toBeNull()
        ->and($user->name)->toBe('updated user')
        ->and($user->email)->toBe('updated_email@example.com')
        ->and(Hash::check('password', $user->password))->toBeTrue()
        ->and($user->hasRole(['admin']))->toBeTrue();
});

test('admin can delete a user', function () {
    $user = User::factory()->create();
    $response = $this->delete(route('users.destroy', $user->id));
    $response->assertRedirect(route('users.index'));
    expect(User::query()->where('id', $user->id)->exists())->toBeFalse();
});

test('admin cannot delete himself', function () {

    $response = $this->actingAs($this->adminUser)
        ->delete(route('users.destroy', $this->adminUser->id));

    $response->assertRedirect()->with('error', __('You cannot delete your own account.'));
    expect(User::query()->where('id', $this->adminUser->id)->exists())->toBeTrue();
});
