<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

final class ResetUserPasswordAction
{
    /**
     * @param  array{token: string, email: string, password: string}  $attribute
     */
    public function handle(array $attribute): string
    {
        /** @var string */
        return Password::reset(
            $attribute,
            function (User $user) use ($attribute): void {
                $user->forceFill([
                    'password' => Hash::make($attribute['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );
    }
}
