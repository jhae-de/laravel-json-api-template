<?php declare(strict_types=1);

namespace App\Enums;

enum RouteName: string
{
    case UserList = 'user:list';
    case UserRead = 'user:read';
    case UserCreate = 'user:create';
    case UserUpdate = 'user:update';
    case UserDelete = 'user:delete';

    public static function forResource(ResourceType $resource, ?ControllerAction ...$controllerActions): array
    {
        $routeNames = match ($resource) {
            ResourceType::User => self::getUserRouteNames(),
        };

        return self::filterByControllerAction($routeNames, ...$controllerActions);
    }

    protected static function filterByControllerAction(
        array $routeNames,
        ?ControllerAction ...$controllerActions,
    ): array {
        if (!$controllerActions) {
            return $routeNames;
        }

        $controllerActionValues = array_map(
            static fn (ControllerAction $controllerAction) => $controllerAction->value,
            $controllerActions,
        );

        return array_intersect_key($routeNames, array_flip($controllerActionValues));
    }

    protected static function getUserRouteNames(): array
    {
        return [
            ControllerAction::List->value => self::UserList->value,
            ControllerAction::Read->value => self::UserRead->value,
            ControllerAction::Create->value => self::UserCreate->value,
            ControllerAction::Update->value => self::UserUpdate->value,
            ControllerAction::Delete->value => self::UserDelete->value,
        ];
    }
}
