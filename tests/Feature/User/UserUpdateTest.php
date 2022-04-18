<?php declare(strict_types=1);

namespace Tests\Feature\User;

use App\Enums\AccessTokenAbility;
use App\Enums\Attribute;
use App\Enums\ResourceType;
use App\Enums\UserRole;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\FeatureTestCase;

class UserUpdateTest extends FeatureTestCase
{
    public function testUpdateAnyUserWithUpdateAnyUserPermissionShouldSucceed(): void
    {
        $resource = User::factory()->create();
        $requestData = $this->getRequestData($resource);

        Sanctum::actingAs(User::factory()->make(), [AccessTokenAbility::UserUpdateAny->value]);

        $this->jsonApi(ResourceType::User->value)
            ->withData($requestData)
            ->patch($this->getUri(ResourceType::User, $resource))
            ->assertFetchedOne($requestData);

        $this->assertDatabaseHas('users', $this->getDatabaseData($resource));
    }

    public function testUpdateAnyUserWithUpdateOwnUserPermissionShouldFail(): void
    {
        $resource = User::factory()->create();
        $requestData = $this->getRequestData($resource);

        Sanctum::actingAs(User::factory()->make(), [AccessTokenAbility::UserUpdateOwn->value]);

        $this->jsonApi(ResourceType::User->value)
            ->withData($requestData)
            ->patch($this->getUri(ResourceType::User, $resource))
            ->assertForbidden();

        $this->assertDatabaseMissing('users', $this->getDatabaseData($resource));
    }

    public function testUpdateOwnUserWithUpdateOwnUserPermissionShouldSucceed(): void
    {
        $user = User::factory()->create();
        $requestData = $this->getRequestData($user);

        Sanctum::actingAs($user, [AccessTokenAbility::UserUpdateOwn->value]);

        $this->jsonApi(ResourceType::User->value)
            ->withData($requestData)
            ->patch($this->getUri(ResourceType::User, $user))
            ->assertFetchedOne($requestData);

        $this->assertDatabaseHas('users', $this->getDatabaseData($user));
    }

    public function testUpdateUserShouldFailOnMissingPermissions(): void
    {
        $user = User::factory()->create();
        $resource = User::factory()->create();

        Sanctum::actingAs($user);

        $requestData = $this->getRequestData($resource);

        $this->jsonApi(ResourceType::User->value)
            ->withData($requestData)
            ->patch($this->getUri(ResourceType::User, $resource))
            ->assertForbidden();

        $this->assertDatabaseMissing('users', $this->getDatabaseData($resource));

        $requestData['id'] = $user->getRouteKey();

        $this->jsonApi(ResourceType::User->value)
            ->withData($requestData)
            ->patch($this->getUri(ResourceType::User, $user))
            ->assertForbidden();

        $this->assertDatabaseMissing('users', $this->getDatabaseData($user));
    }

    protected function getRequestData(User $user): array
    {
        return [
            'type' => ResourceType::User,
            'id' => $user->getRouteKey(),
            'attributes' => [
                Attribute::FirstName->api() => 'John',
                Attribute::LastName->api() => 'Doe',
                Attribute::Email->api() => 'john.doe@example.com',
                Attribute::Role->api() => UserRole::User->value,
            ],
        ];
    }

    protected function getDatabaseData(User $user): array
    {
        return [
            Attribute::Id->model() => $user->{Attribute::Id->model()},
            Attribute::FirstName->model() => 'John',
            Attribute::LastName->model() => 'Doe',
            Attribute::Email->model() => 'john.doe@example.com',
            Attribute::Role->model() => UserRole::User->value,
        ];
    }
}
