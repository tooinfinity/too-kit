<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Password;

final class SendPasswordResetLinkAction
{
    /**
     * Send a password reset link to the given user.
     *
     * @param  array{email: string}  $credentials
     */
    public function handle(array $credentials): string
    {
        return Password::sendResetLink($credentials);
    }
}
