<?php declare(strict_types=1);

namespace App\Enums;

enum PageSize: int
{
    case S10 = 10;
    case S25 = 25;
    case S50 = 50;
    case S100 = 100;
}
