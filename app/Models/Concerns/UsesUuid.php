<?php declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait UsesUuid
{
    protected static function bootUsesUuid(): void
    {
        static::creating(static function (Model $model): void {
            if ($model->getKey() !== null) {
                return;
            }

            $model->setAttribute($model->getKeyName(), Str::orderedUuid()->toString());
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }
}
