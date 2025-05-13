<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case CASHIER = 'cashier';

    /**
     * @return array<int, string>
     */
    public static function toArray(): array
    {
        return array_map(static fn (self $enum) => $enum->value, self::cases());
    }
}
