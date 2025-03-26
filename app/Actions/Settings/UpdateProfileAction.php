<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\Models\User;

final class UpdateProfileAction
{
    /**
     * Update the user's profile settings.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes, User $user): void
    {
        $user->fill($attributes);
        if ($user->isDirty('email')) {
            $user->forceFill(['email_verified_at' => null]);
        }

        $user->save();
    }
}
