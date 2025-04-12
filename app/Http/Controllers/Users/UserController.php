<?php

declare(strict_types=1);

namespace App\Http\Controllers\Users;

use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\StoreUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Http\Requests\Users\UserCreateRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

final class UserController
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', User::class);

        $search = $request->string('search')->value();
        $perPage = $request->integer('per_page', 10);
        $sort = $request->string('sort')->value();

        $users = User::with('roles')
            ->when($search, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        if ($sort) {
            $direction = 'asc';
            if (str_starts_with($sort, '-')) {
                $direction = 'desc';
                $sort = mb_ltrim($sort, '-');
            }
            $users->orderBy($sort, $direction);
        }

        $users = $users->paginate($perPage);

        return Inertia::render('users/index', [
            'users' => UserResource::collection($users),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     */
    public function create(): Response
    {
        $this->authorize('create', User::class);

        $roles = Role::query()->select('id', 'name')->get();

        return Inertia::render('users/create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws AuthorizationException
     */
    public function store(UserCreateRequest $request, StoreUserAction $action): RedirectResponse
    {
        $this->authorize('create', User::class);

        /** @var array{name: string, email: string, password: string, role?: string} $validated */
        $validated = $request->validated();
        $action->handle($validated);

        return to_route('users.index')
            ->with('success', __('User created successfully.'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws AuthorizationException
     */
    public function edit(User $user): Response
    {
        $this->authorize('update', $user);

        $roles = Role::query()->select('id', 'name')->get();

        return Inertia::render('users/edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws AuthorizationException
     */
    public function update(UserUpdateRequest $request, User $user, UpdateUserAction $action): RedirectResponse
    {
        $this->authorize('update', $user);

        /** @var array{name: string, email: string, password?: string, roles?: array<string>} $validated */
        $validated = $request->validated();
        $action->handle($validated, $user);

        return to_route('users.index')
            ->with('success', __('User updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(User $user, DeleteUserAction $action): RedirectResponse
    {
        $this->authorize('delete', $user);

        if ($action->handle($user)) {
            return to_route('users.index')
                ->with('success', __('User deleted successfully.'));
        }

        return back()->with('error', __('You cannot delete your own account.'));
    }
}
