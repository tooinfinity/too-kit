<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('password reset validates input', function () {
    $response = $this->post('/reset-password', []);

    $response->assertSessionHasErrors(['token', 'email', 'password']);
});

test('password reset fails with invalid token', function () {
    $user = User::factory()->create();

    $response = $this->post('/reset-password', [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertSessionHasErrors('email');
});

test('password can be reset with valid token', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $response = $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('login'));
    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});
