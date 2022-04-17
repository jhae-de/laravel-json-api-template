<?php declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Enums\AccessTokenAbility;
use App\Enums\Attribute;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\NewAccessToken;

class LoginController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function __invoke(LoginRequest $request): Response
    {
        $user = $request->authenticate();
        $accessToken = $this->issueAccessToken($user);

        return response([
            'accessToken' => $accessToken->plainTextToken,
            'permissions' => $accessToken->accessToken->getAttribute('abilities'),
        ]);
    }

    protected function issueAccessToken(User $user): NewAccessToken
    {
        $user->tokens()->delete();

        return $user->createToken(
            'accessToken',
            AccessTokenAbility::forUserRole($user->{Attribute::Role->model()})
        );
    }
}
