<?php declare(strict_types=1);

namespace App\JsonApi\V1\Users;

use App\Enums\Attribute;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use LaravelJsonApi\Core\Resources\JsonApiResource;

class UserResource extends JsonApiResource
{
    /**
     * @param ?Request $request
     */
    public function attributes($request): iterable
    {
        return [
            Attribute::FirstName->api() => $this->{Attribute::FirstName->model()},
            Attribute::LastName->api() => $this->{Attribute::LastName->model()},
            Attribute::Email->api() => $this->{Attribute::Email->model()},
            Attribute::EmailVerifiedAt->api() => $this->when(
                $this->shouldAddEmailVerifiedAtAttribute($request),
                $this->{Attribute::EmailVerifiedAt->model()}
            ),
            Attribute::Role->api() => $this->{Attribute::Role->model()},
            Attribute::CreatedAt->api() => $this->{Attribute::CreatedAt->model()},
            Attribute::UpdatedAt->api() => $this->{Attribute::UpdatedAt->model()},
        ];
    }

    protected function shouldAddEmailVerifiedAtAttribute(?Request $request): bool
    {
        /** @var ?User $user */
        $user = $request?->user();

        return $user?->hasRole(UserRole::Admin) || $user?->{Attribute::Id->model()} === $this->id();
    }
}
