<?php

declare(strict_types=1);

test('role enum can be cast to string', function () {
    $role = App\Enums\RoleEnum::ADMIN;
    expect($role->value)->toBe('admin');
});

test('roles enum can be cast to array', function () {
    $roles = App\Enums\RoleEnum::toArray();
    expect($roles)->toBe([
        '0' => App\Enums\RoleEnum::ADMIN->value,
        '1' => App\Enums\RoleEnum::MANAGER->value,
        '2' => App\Enums\RoleEnum::CASHIER->value,
    ]);

});
