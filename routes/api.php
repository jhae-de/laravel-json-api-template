<?php declare(strict_types=1);

use App\Enums\ControllerAction;
use App\Enums\ResourceType;
use App\Enums\RouteName;
use App\JsonApi\V1\Server as JsonApiServer;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use LaravelJsonApi\Laravel\Routing\ResourceRegistrar as Server;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

JsonApiRoute::server(JsonApiServer::VERSION)
    ->middleware('auth:sanctum')
    ->prefix(JsonApiServer::VERSION)
    ->name('')
    ->resources(static function (Server $server) {
        $routes = config('routes.resources', []);

        foreach ($routes as $resourceTypeValue => $route) {
            $resourceType = ResourceType::from($resourceTypeValue);
            $controllerActions = $route['controllerActions'] ?? [];

            $resource = $server->resource($resourceType->value, $route['controller'] ?? JsonApiController::class);

            if ($controllerActions) {
                $controllerActionValues = array_map(
                    static fn (ControllerAction $controllerAction) => $controllerAction->value,
                    $controllerActions
                );

                $resource->only(...$controllerActionValues);
            }

            $resource->names(RouteName::forResource($resourceType, ...$controllerActions));
        }
    });
