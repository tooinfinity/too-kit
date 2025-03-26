<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

final class CreateNewUserAction
{
    /**
     * @param  array{name: string, email: string, password: string}  $attribute
     */
    public function handle(array $attribute): User
    {
        $user = User::create([
            'name' => $attribute['name'],
            'email' => $attribute['email'],
            'password' => Hash::make($attribute['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return $user;
    }
}
