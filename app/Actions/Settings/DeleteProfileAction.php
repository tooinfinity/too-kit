<?php

declare(strict_types=1);

namespace App\Actions\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class DeleteProfileAction
{
    public function handle(User $user): void
    {
        Auth::logout();

        $user->delete();
    }
}
