<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;

final class DeleteUserAction
{
    public function handle(User $user): bool
    {
        if ($user->id === auth()->id()) {
            return false;
        }
        $user->delete();

        return true;
    }
}
