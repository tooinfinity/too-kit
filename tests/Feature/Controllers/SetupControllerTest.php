<?php

declare(strict_types=1);

use App\Enums\RoleEnum;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

test('setup page can be rendered', function () {
    $response = $this->get('/setup');
    $response->assertStatus(200);
});

test('setup admin user can be stored', function () {
    $response = $this->post(route('setup.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::query()->where('email', 'test@example.com')->first();
    $adminRole = Role::query()->where('name', RoleEnum::ADMIN->value)->first();
    $user->assignRole($adminRole);
    Setting::markSetupCompleted();
    $user->markEmailAsVerified();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('Test User')
        ->and($user->email)->toBe('test@example.com')
        ->and(Hash::check('password', $user->password))->toBeTrue()
        ->and($user->hasRole('admin'))->toBeTrue()
        ->and(Setting::setupCompleted())->toBeTrue();

    $this->assertAuthenticated();
    $response->assertRedirect(route('login', absolute: false));
});
