<?php declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Enums\Attribute;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            Attribute::Email->api() => ['email', 'required'],
            Attribute::Password->api() => ['string', 'required'],
        ];
    }

    /**
     * @throws ValidationException
     */
    public function authenticate(): User
    {
        $this->ensureIsNotRateLimited();

        $email = $this->validated(Attribute::Email->api());
        $password = $this->validated(Attribute::Password->api());

        $user = User::where(Attribute::Email->model(), $email)->first();

        $rateLimiterKey = $this->getRateLimiterKey();

        if (!$user || !Hash::check($password, $user->{Attribute::Password->model()})) {
            RateLimiter::hit($rateLimiterKey, config('auth.rate_limiter.decay_seconds', 60));

            throw ValidationException::withMessages([
                Attribute::Email->api() => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($rateLimiterKey);

        $this->ensureEmailIsVerified($user);

        return $user;
    }

    /**
     * @throws ValidationException
     */
    protected function ensureIsNotRateLimited(): void
    {
        $rateLimiterKey = $this->getRateLimiterKey();

        if (!RateLimiter::tooManyAttempts($rateLimiterKey, config('auth.rate_limiter.max_login_attempts', 5))) {
            return;
        }

        throw ValidationException::withMessages([
            Attribute::Email->api() => trans('auth.throttled', [
                'seconds' => RateLimiter::availableIn($rateLimiterKey),
            ]),
        ]);
    }

    /**
     * @throws ValidationException
     */
    protected function ensureEmailIsVerified(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        throw ValidationException::withMessages([
            Attribute::Email->api() => trans('auth.unverified'),
        ]);
    }

    protected function getRateLimiterKey(): string
    {
        return Str::lower($this->validated(Attribute::Email->api())) . '|' . $this->ip();
    }
}
