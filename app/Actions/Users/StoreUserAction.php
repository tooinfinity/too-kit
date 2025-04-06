<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;

final class StoreUserAction
{
    /**
     * Handle the action of creating a new user.
     *
     * @param  array{name: string, email: string, password: string, roles?: array<string>, permissions?: array<string>}  $attributes  The attributes for the new user.
     */
    public function handle(array $attributes): User
    {
        return DB::transaction(function () use ($attributes): User {
            $user = User::query()->create([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'password' => $attributes['password'],
                'email_verified_at' => now(),
            ]);

            if (! empty($attributes['roles'])) {
                $user->syncRoles($attributes['roles']);
            }

            if (! empty($attributes['permissions'])) {
                $user->syncPermissions($attributes['permissions']);
            }

            return $user;
        });

        // todo: send email verification
        // todo: audit log
        // todo: notify admin
    }
}
