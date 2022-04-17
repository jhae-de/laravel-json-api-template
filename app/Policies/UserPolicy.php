<?php declare(strict_types=1);

namespace App\Policies;

use App\Enums\AccessTokenAbility;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->tokenCan(AccessTokenAbility::UserList->value);
    }

    public function view(User $user, User $model): bool
    {
        return $user->tokenCan(AccessTokenAbility::UserReadAny->value)
            || $this->tokenCanOwn(AccessTokenAbility::UserReadOwn, $user, $model);
    }

    public function create(User $user): bool
    {
        return $user->tokenCan(AccessTokenAbility::UserCreate->value);
    }

    public function update(User $user, User $model): bool
    {
        return $user->tokenCan(AccessTokenAbility::UserUpdateAny->value)
            || $this->tokenCanOwn(AccessTokenAbility::UserUpdateOwn, $user, $model);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->tokenCan(AccessTokenAbility::UserDeleteAny->value)
            || $this->tokenCanOwn(AccessTokenAbility::UserDeleteOwn, $user, $model);
    }

    protected function tokenCanOwn(AccessTokenAbility $ability, User $user, User $model): bool
    {
        return $user->tokenCan($ability->value) && $user->is($model);
    }
}
