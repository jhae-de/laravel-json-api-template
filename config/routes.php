<?php declare(strict_types=1);

use App\Enums\ResourceType;

return [
    'max_requests_per_minute' => 60,

    'resources' => [
        ResourceType::User->value => [],
    ],
];
