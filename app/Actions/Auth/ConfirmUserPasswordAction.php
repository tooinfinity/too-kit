<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

final class ConfirmUserPasswordAction
{
    public function handle(string $email, string $password): bool
    {
        return Auth::guard('web')->validate([
            'email' => $email,
            'password' => $password,
        ]);
    }
}
