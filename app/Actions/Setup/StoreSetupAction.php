<?php

declare(strict_types=1);

namespace App\Actions\Setup;

use App\Actions\Auth\CreateNewUserAction;
use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Setting;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class StoreSetupAction
{
    /**
     * @param  array{name: string, email: string, password: string}  $attributes
     */
    public function handle(array $attributes, CreateNewUserAction $newUserAction): void
    {
        // register user
        $user = $newUserAction->handle($attributes);
        $user->markEmailAsVerified();
        // create admin role
        $adminRole = Role::create(['name' => RoleEnum::ADMIN->value]);
        // create permissions
        $permissions = PermissionEnum::toArray();
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        // assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        // assign admin role to first user
        $user->assignRole($adminRole);
        // mark setup as completed
        Setting::markSetupCompleted();
    }
}
