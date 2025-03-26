<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;

final class SendEmailVerificationNotificationAction
{
    public function handle(User $user): void
    {
        $user->sendEmailVerificationNotification();
    }
}
