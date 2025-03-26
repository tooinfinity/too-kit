<?php

declare(strict_types=1);

use App\Models\User;

test('verification notice is shown to unverified users', function () {
    $user = User::factory()->unverified()->create();

    $response = $this
        ->actingAs($user)
        ->get('/verify-email');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('auth/verify-email')
        ->has('status')
    );
});

test('verified users are redirected from verification notice', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/verify-email');

    $response->assertRedirect(route('dashboard', absolute: false));
});
