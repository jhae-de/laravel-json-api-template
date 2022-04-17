<?php declare(strict_types=1);

namespace Database\Seeders\DatabaseSeeder;

use App\Enums\Attribute;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        collect($this->getData())->each(static function (array $data) {
            User::factory()
                ->markEmailAsVerified()
                ->create($data);
        });

        User::factory(10)
            ->markEmailAsVerified()
            ->create();
    }

    protected function getData(): iterable
    {
        yield [
            Attribute::Email->model() => 'admin@localhost',
            Attribute::Role->model() => UserRole::Admin,
        ];

        yield [
            Attribute::Email->model() => 'user@localhost',
        ];
    }
}
