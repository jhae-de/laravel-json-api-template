<?php declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Attribute;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            Attribute::FirstName->model() => $this->faker->firstName(),
            Attribute::LastName->model() => $this->faker->lastName(),
            Attribute::Email->model() => $this->faker->unique()->safeEmail(),
            Attribute::Password->model() => 'password',
            Attribute::Role->model() => UserRole::User,
        ];
    }

    public function markEmailAsVerified(): static
    {
        $callback = static function (User $user) {
            $user->markEmailAsVerified();
        };

        return $this
            ->afterMaking($callback)
            ->afterCreating($callback);
    }
}
