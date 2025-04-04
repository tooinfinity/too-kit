<?php

declare(strict_types=1);

namespace App\Enums;

enum PermissionEnum: string
{
    case VIEW_DASHBOARD = 'view dashboard';
    case VIEW_USERS = 'view users';
    case CREATE_USERS = 'create users';
    case EDIT_USERS = 'edit users';
    case DELETE_USERS = 'delete users';
    case VIEW_ROLES = 'view roles';
    case CREATE_ROLES = 'create roles';
    case EDIT_ROLES = 'edit roles';
    case DELETE_ROLES = 'delete roles';
    case ASSIGN_ROLES = 'assign roles';
    case VIEW_PERMISSIONS = 'view permissions';
    case GRANT_PERMISSIONS = 'grant permissions';
    case REVOKE_PERMISSIONS = 'revoke permissions';
    case VIEW_SETTINGS = 'view settings';

    /**
     * @return array<int, string>
     */
    public static function toArray(): array
    {
        return array_map(static fn (self $enum) => $enum->value, self::cases());
    }
}
