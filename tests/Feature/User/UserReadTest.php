<?php declare(strict_types=1);

namespace Tests\Feature\User;

use App\Enums\AccessTokenAbility;
use App\Enums\ResourceType;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\FeatureTestCase;

class UserReadTest extends FeatureTestCase
{
    public function testReadAnyUserWithReadAnyUserPermissionShouldSucceed(): void
    {
        $resource = User::factory()->create();

        Sanctum::actingAs(User::factory()->make(), [AccessTokenAbility::UserReadAny->value]);

        $this->jsonApi(ResourceType::User->value)
            ->get($this->getUri(ResourceType::User, $resource))
            ->assertFetchedOne($resource);
    }

    public function testReadAnyUserWithReadOwnUserPermissionShouldFail(): void
    {
        $resource = User::factory()->create();

        Sanctum::actingAs(User::factory()->make(), [AccessTokenAbility::UserReadOwn->value]);

        $this->jsonApi(ResourceType::User->value)
            ->get($this->getUri(ResourceType::User, $resource))
            ->assertForbidden();
    }

    public function testReadOwnUserWithReadOwnUserPermissionShouldSucceed(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user, [AccessTokenAbility::UserReadOwn->value]);

        $this->jsonApi(ResourceType::User->value)
            ->get($this->getUri(ResourceType::User, $user))
            ->assertFetchedOne($user);
    }

    public function testReadUserShouldFailOnMissingPermissions(): void
    {
        $user = User::factory()->create();
        $resource = User::factory()->create();

        Sanctum::actingAs($user);

        $this->jsonApi(ResourceType::User->value)
            ->get($this->getUri(ResourceType::User, $user))
            ->assertForbidden();

        $this->jsonApi(ResourceType::User->value)
            ->get($this->getUri(ResourceType::User, $resource))
            ->assertForbidden();
    }
}
