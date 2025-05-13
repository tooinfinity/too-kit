<?php

declare(strict_types=1);

namespace App\Actions\Setup;

use App\Actions\Auth\CreateNewUserAction;
use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class StoreSetupAction
{
    /**
     * @param  array{name: string, email: string, password: string}  $attributes
     */
    public function handle(array $attributes, CreateNewUserAction $newUserAction): void
    {
        DB::transaction(function () use ($attributes, $newUserAction): void {
            $user = $newUserAction->handle($attributes);
            $user->markEmailAsVerified();

            $adminRole = Role::create(['name' => RoleEnum::ADMIN->value]);
            Role::create(['name' => RoleEnum::MANAGER->value]);
            Role::create(['name' => RoleEnum::CASHIER->value]);
            $permissions = PermissionEnum::toArray();

            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
            }

            $adminRole->givePermissionTo(Permission::all());
            $user->assignRole($adminRole);

            Setting::markSetupCompleted();
        });
    }
}
