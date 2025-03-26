<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class UpdatePasswordAction
{
    /**
     * Update the user's password.
     *
     * @param  array<string, string>  $attribute
     */
    public function handle(array $attribute, User $user): void
    {
        $user->update([
            'password' => Hash::make($attribute['password']),
        ]);
    }
}
