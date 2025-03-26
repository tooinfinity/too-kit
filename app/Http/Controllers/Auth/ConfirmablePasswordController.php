<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\ConfirmUserPasswordAction;
use App\Http\Requests\Auth\ConfirmPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

final class ConfirmablePasswordController
{
    /**
     * Show the confirm password page.
     */
    public function show(): Response
    {
        return Inertia::render('auth/confirm-password');
    }

    /**
     * Confirm the user's password.
     *
     * @throws ValidationException
     */
    public function store(ConfirmPasswordRequest $request, ConfirmUserPasswordAction $action): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        if (! $action->handle($user->email, $request->string('password')->value())) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
