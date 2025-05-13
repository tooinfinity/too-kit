<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;

final class UpdateUserAction
{
    /**
     * Handle the action of updating an existing user.
     *
     * @param  array{name: string, email: string, password?: string, roles?: array<string>}  $attributes  The attributes for the user.
     * @param  User  $user  The user instance to update.
     */
    public function handle(array $attributes, User $user): User
    {
        return DB::transaction(function () use ($attributes, $user): User {
            $updatedAttributes = [
                'name' => $attributes['name'],
                'email' => $attributes['email'],
            ];

            if (! empty($attributes['password'])) {
                $updatedAttributes['password'] = $attributes['password'];
            }
            $user->update($updatedAttributes);

            $user->syncRoles($attributes['roles'] ?? []);

            return $user;
        });

        // todo: send email verification
        // todo: audit log
        // todo: notify admin
    }
}
