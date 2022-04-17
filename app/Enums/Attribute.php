<?php declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum Attribute
{
    case CreatedAt;
    case Email;
    case EmailVerifiedAt;
    case FirstName;
    case Id;
    case LastName;
    case Password;
    case RememberToken;
    case Role;
    case UpdatedAt;

    public function api(): string
    {
        return Str::camel($this->name);
    }

    public function model(): string
    {
        return Str::snake($this->name);
    }
}
