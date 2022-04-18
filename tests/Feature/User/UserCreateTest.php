<?php declare(strict_types=1);

namespace Tests\Feature\User;

use App\Enums\AccessTokenAbility;
use App\Enums\Attribute;
use App\Enums\ResourceType;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\FeatureTestCase;

class UserCreateTest extends FeatureTestCase
{
    public function testCreateUserShouldSucceed(): void
    {
        $resource = User::factory()->make();
        $requestData = $this->getRequestData($resource);

        Sanctum::actingAs(User::factory()->make(), [AccessTokenAbility::UserCreate->value]);

        $response = $this->jsonApi(ResourceType::User->value)
            ->withData($requestData)
            ->post($this->getUri(ResourceType::User))
            ->assertCreatedWithServerId($this->getUri(ResourceType::User), $requestData);

        $this->assertDatabaseHas('users', [
            Attribute::Id->model() => $response->getId(),
            ...$this->getDatabaseData($resource),
        ]);
    }

    public function testCreateUserShouldFail(): void
    {
        $resource = User::factory()->make();
        $requestData = $this->getRequestData($resource);

        Sanctum::actingAs(User::factory()->make());

        $this->jsonApi(ResourceType::User->value)
            ->withData($requestData)
            ->post($this->getUri(ResourceType::User))
            ->assertForbidden();

        $this->assertDatabaseMissing('users', $this->getDatabaseData($resource));
    }

    protected function getRequestData(User $user): array
    {
        return [
            'type' => ResourceType::User,
            'attributes' => [
                Attribute::FirstName->api() => $user->{Attribute::FirstName->model()},
                Attribute::LastName->api() => $user->{Attribute::LastName->model()},
                Attribute::Email->api() => $user->{Attribute::Email->model()},
                Attribute::Role->api() => $user->{Attribute::Role->model()},
            ],
        ];
    }

    protected function getDatabaseData(User $user): array
    {
        return [
            Attribute::FirstName->model() => $user->{Attribute::FirstName->model()},
            Attribute::LastName->model() => $user->{Attribute::LastName->model()},
            Attribute::Email->model() => $user->{Attribute::Email->model()},
            Attribute::Role->model() => $user->{Attribute::Role->model()},
        ];
    }
}
