<?php declare(strict_types=1);

namespace Tests\Feature\User;

use App\Enums\AccessTokenAbility;
use App\Enums\ResourceType;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\FeatureTestCase;

class UserListTest extends FeatureTestCase
{
    public function testListUsersShouldSucceed(): void
    {
        $resources = User::factory(3)->create();

        Sanctum::actingAs(User::factory()->make(), [AccessTokenAbility::UserList->value]);

        $this->jsonApi(ResourceType::User->value)
            ->get($this->getUri(ResourceType::User))
            ->assertFetchedMany($resources);
    }

    public function testListUsersShouldFail(): void
    {
        Sanctum::actingAs(User::factory()->make());

        $this->jsonApi(ResourceType::User->value)
            ->get($this->getUri(ResourceType::User))
            ->assertForbidden();
    }
}
