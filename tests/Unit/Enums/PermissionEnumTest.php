<?php

declare(strict_types=1);

test('permission enum can be cast to string', function () {
    $permission = App\Enums\PermissionEnum::CREATE_USERS;
    expect($permission->value)->toBe('create users');
});

test('permissions can be array', function () {
    $permissions = App\Enums\PermissionEnum::toArray();
    expect($permissions)->toBe([
        0 => 'view dashboard',
        1 => 'view users',
        2 => 'create users',
        3 => 'edit users',
        4 => 'delete users',
        5 => 'view roles',
        6 => 'create roles',
        7 => 'edit roles',
        8 => 'delete roles',
        9 => 'assign roles',
        10 => 'view permissions',
        11 => 'grant permissions',
        12 => 'revoke permissions',
        13 => 'view settings',
    ]);
});
