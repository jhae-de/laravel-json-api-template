<?php declare(strict_types=1);

namespace App\JsonApi\V1\Users;

use App\Enums\Attribute;
use App\Enums\PageSize;
use App\Models\User;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;
use LaravelJsonApi\Eloquent\Sorting\SortColumn;

class UserSchema extends Schema
{
    public static string $model = User::class;

    protected ?array $defaultPagination = ['number' => 1];

    public function fields(): array
    {
        return [
            ID::make()->uuid(),
            Str::make(Attribute::FirstName->api()),
            Str::make(Attribute::LastName->api()),
            Str::make(Attribute::Email->api()),
            Str::make(Attribute::Role->api()),
        ];
    }

    public function sortables(): iterable
    {
        return [
            SortColumn::make(Attribute::FirstName->api()),
            SortColumn::make(Attribute::LastName->api()),
            SortColumn::make(Attribute::CreatedAt->api()),
            SortColumn::make(Attribute::UpdatedAt->api()),
        ];
    }

    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
            Where::make(Attribute::FirstName->api()),
            Where::make(Attribute::LastName->api()),
            Where::make(Attribute::Email->api()),
            Where::make(Attribute::Role->api()),
        ];
    }

    public function pagination(): ?Paginator
    {
        return PagePagination::make()->withDefaultPerPage(PageSize::S25->value);
    }
}
