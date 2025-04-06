<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\StoreUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Http\Requests\Users\UserCreateRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $users = User::query()
            ->with(['roles', 'permissions'])
            ->latest()
            ->paginate(10);

        return Inertia::render('users/index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $roles = Role::query()->select('id', 'name')->get();
        $permissions = Permission::query()->select('id', 'name')->get();

        return Inertia::render('users/create', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request, StoreUserAction $action): RedirectResponse
    {
        /** @var array{name: string, email: string, password: string, role?: string, permissions?: array<string>} $validated */
        $validated = $request->validated();
        $action->handle($validated);

        return to_route('users.index')
            ->with('success', __('User created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response
    {
        $roles = Role::query()->select('id', 'name')->get();
        $permissions = Permission::query()->select('id', 'name')->get();

        return Inertia::render('users/edit', [
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user, UpdateUserAction $action): RedirectResponse
    {
        /** @var array{name: string, email: string, password?: string, roles?: array<string>, permissions?: array<string>} $validated */
        $validated = $request->validated();
        $action->handle($validated, $user);

        return to_route('users.index')
            ->with('success', __('User updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, DeleteUserAction $action): RedirectResponse
    {
        if ($action->handle($user)) {
            return to_route('users.index')
                ->with('success', __('User deleted successfully.'));
        }

        return back()->with('error', __('You cannot delete your own account.'));
    }
}
