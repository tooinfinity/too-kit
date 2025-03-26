<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\CreateNewUserAction;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class RegisteredUserController
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request, CreateNewUserAction $action): RedirectResponse
    {
        /** @var array{email: string, name: string, password: string} $attribute */
        $attribute = $request->validated();
        $action->handle($attribute);

        return to_route('dashboard');
    }
}
