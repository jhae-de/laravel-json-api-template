<?php declare(strict_types=1);

namespace App\JsonApi\V1\Users;

use App\Enums\Attribute;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Unique;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class UserRequest extends ResourceRequest
{
    public function rules(): array
    {
        $model = $this->model();

        $uniqueEmail = new Unique('users', Attribute::Email->model());

        if ($model instanceof Model) {
            $uniqueEmail->ignoreModel($model);
        }

        return [
            Attribute::FirstName->api() => ['string', 'required'],
            Attribute::LastName->api() => ['string', 'required'],
            Attribute::Email->api() => ['email', 'required', $uniqueEmail],
            Attribute::Role->api() => ['string', 'required', new Enum(UserRole::class)],
        ];
    }
}
