<?php declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\Attribute;
use App\Enums\UserRole;

trait HasUserRole
{
    public function hasRole(UserRole ...$roles): bool
    {
        return in_array($this->{Attribute::Role->model()}, $roles, true);
    }
}
