<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

test('verification notification can be sent', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $response = $this
        ->actingAs($user)
        ->post('/email/verification-notification');

    $response->assertRedirect();
    $response->assertSessionHas('status', 'verification-link-sent');
    Notification::assertSentTo($user, VerifyEmail::class);
});

test('verification notification is not sent to verified users', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/email/verification-notification');

    $response->assertRedirect(route('dashboard', absolute: false));
});
