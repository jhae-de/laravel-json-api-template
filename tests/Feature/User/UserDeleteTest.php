<?php declare(strict_types=1);

namespace Tests\Feature\User;

use App\Enums\AccessTokenAbility;
use App\Enums\Attribute;
use App\Enums\ResourceType;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\FeatureTestCase;

class UserDeleteTest extends FeatureTestCase
{
    public function testDeleteAnyUserWithDeleteAnyUserPermissionShouldSucceed(): void
    {
        $resource = User::factory()->create();

        Sanctum::actingAs(User::factory()->make(), [AccessTokenAbility::UserDeleteAny->value]);

        $this->jsonApi(ResourceType::User->value)
            ->delete($this->getUri(ResourceType::User, $resource))
            ->assertNoContent();

        $this->assertDatabaseMissing('users', [
            Attribute::Id->model() => $resource->{Attribute::Id->model()},
        ]);
    }

    public function testDeleteAnyUserWithDeleteOwnUserPermissionShouldFail(): void
    {
        $resource = User::factory()->create();

        Sanctum::actingAs(User::factory()->make(), [AccessTokenAbility::UserDeleteOwn->value]);

        $this->jsonApi(ResourceType::User->value)
            ->delete($this->getUri(ResourceType::User, $resource))
            ->assertForbidden();

        $this->assertDatabaseHas('users', [
            Attribute::Id->model() => $resource->{Attribute::Id->model()},
        ]);
    }

    public function testDeleteOwnUserWithDeleteOwnUserPermissionShouldSucceed(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, [AccessTokenAbility::UserDeleteOwn->value]);

        $this->jsonApi(ResourceType::User->value)
            ->delete($this->getUri(ResourceType::User, $user))
            ->assertNoContent();

        $this->assertDatabaseMissing('users', [
            Attribute::Id->model() => $user->{Attribute::Id->model()},
        ]);
    }

    public function testDeleteUserShouldFailOnMissingPermissions(): void
    {
        $user = User::factory()->create();
        $resource = User::factory()->create();

        Sanctum::actingAs($user);

        $this->jsonApi(ResourceType::User->value)
            ->delete($this->getUri(ResourceType::User, $resource))
            ->assertForbidden();

        $this->assertDatabaseHas('users', [
            Attribute::Id->model() => $resource->{Attribute::Id->model()},
        ]);

        $this->jsonApi(ResourceType::User->value)
            ->delete($this->getUri(ResourceType::User, $user))
            ->assertForbidden();

        $this->assertDatabaseHas('users', [
            Attribute::Id->model() => $user->{Attribute::Id->model()},
        ]);
    }
}
