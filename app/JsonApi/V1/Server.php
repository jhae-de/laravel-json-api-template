<?php declare(strict_types=1);

namespace App\JsonApi\V1;

use LaravelJsonApi\Core\Server\Server as BaseServer;

class Server extends BaseServer
{
    final public const VERSION = 'v1';

    protected string $baseUri = '/api/v1';

    protected function allSchemas(): array
    {
        return [
            Users\UserSchema::class,
        ];
    }
}
