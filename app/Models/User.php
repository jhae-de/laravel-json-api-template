<?php declare(strict_types=1);

namespace App\Models;

use App\Enums\Attribute;
use App\Enums\UserRole;
use App\Models\Concerns\HasUserRole;
use App\Models\Concerns\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasUserRole;
    use Notifiable;
    use UsesUuid;

    public function getFillable(): array
    {
        return array_unique([
            ...parent::getFillable(),
            Attribute::FirstName->model(),
            Attribute::LastName->model(),
            Attribute::Email->model(),
            Attribute::Role->model(),
        ]);
    }

    public function getHidden(): array
    {
        return array_unique([
            ...parent::getHidden(),
            Attribute::Password->model(),
            Attribute::RememberToken->model(),
        ]);
    }

    public function getCasts(): array
    {
        return array_unique([
            ...parent::getCasts(),
            Attribute::EmailVerifiedAt->model() => 'datetime',
            Attribute::Role->model() => UserRole::class,
        ]);
    }
}
