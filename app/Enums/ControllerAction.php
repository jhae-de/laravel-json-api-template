<?php declare(strict_types=1);

namespace App\Enums;

enum ControllerAction: string
{
    case List = 'index';
    case Read = 'show';
    case Create = 'store';
    case Update = 'update';
    case Delete = 'destroy';
}
