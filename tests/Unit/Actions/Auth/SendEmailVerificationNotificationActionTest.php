<?php

declare(strict_types=1);

use App\Actions\Auth\SendEmailVerificationNotificationAction;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

test('it sends email verification notification', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();
    $action = new SendEmailVerificationNotificationAction();

    $action->handle($user);

    Notification::assertSentTo($user, VerifyEmail::class);
});
