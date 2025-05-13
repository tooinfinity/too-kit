<?php

declare(strict_types=1);

namespace App\Http\Controllers\Setup;

use App\Actions\Auth\CreateNewUserAction;
use App\Actions\Setup\StoreSetupAction;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class SetupController
{
    /**
     * Show the setup page.
     */
    public function index(): Response
    {
        return Inertia::render('setup');
    }

    public function store(RegisterRequest $request, CreateNewUserAction $newUserAction, StoreSetupAction $action): RedirectResponse
    {
        /** @var array{name: string, email: string, password: string} $validated */
        $validated = $request->validated();
        $action->handle($validated, $newUserAction);

        return to_route('login')->with('success', 'Setup completed successfully.');

    }
}
