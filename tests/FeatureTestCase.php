<?php declare(strict_types=1);

namespace Tests;

use App\Enums\ResourceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use LaravelJsonApi\Testing\MakesJsonApiRequests;

abstract class FeatureTestCase extends TestCase
{
    use CreatesApplication;
    use MakesJsonApiRequests;
    use RefreshDatabase;

    protected function getUri(ResourceType $resourceType, ?Model $model = null): string
    {
        $baseUri = env('APP_URL') . '/api/v1';

        return $model !== null
            ? sprintf('%s/%s/%s', $baseUri, $resourceType->value, $model->getRouteKey())
            : sprintf('%s/%s', $baseUri, $resourceType->value);
    }
}
