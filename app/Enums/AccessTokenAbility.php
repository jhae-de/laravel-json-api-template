<?php declare(strict_types=1);

namespace App\Enums;

enum AccessTokenAbility: string
{
    case UserList = 'user:list';
    case UserReadAny = 'user:read:any';
    case UserReadOwn = 'user:read:own';
    case UserCreate = 'user:create';
    case UserUpdateAny = 'user:update:any';
    case UserUpdateOwn = 'user:update:own';
    case UserDeleteAny = 'user:delete:any';
    case UserDeleteOwn = 'user:delete:own';

    public static function forUserRole(UserRole $userRole): array
    {
        $abilities = match ($userRole) {
            UserRole::Admin => self::getAdminAbilities(),
            UserRole::User => self::getUserAbilities(),
        };

        return array_map(static fn (AccessTokenAbility $ability) => $ability->value, $abilities);
    }

    protected static function getAdminAbilities(): array
    {
        return [
            self::UserList,
            self::UserReadAny,
            self::UserCreate,
            self::UserUpdateAny,
            self::UserDeleteAny,
        ];
    }

    protected static function getUserAbilities(): array
    {
        return [
            self::UserReadOwn,
            self::UserUpdateOwn,
            self::UserDeleteOwn,
        ];
    }
}
