<?php

namespace Modules\Authorization\Infrastructure\Mappers;

use Illuminate\Support\Collection;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Authorization\Infrastructure\Models\PermissionModel;

class PermissionMapper
{

    public static function toDomainEntity(PermissionModel|Collection $model): Permission|array
    {
        if ($model instanceof Collection) {
            return $model
                ->map(fn(PermissionModel $permission) => self::toDomainEntity($permission))
                ->all();
        }
        return new Permission(
            id: new PermissionId($model->id),
            name: $model->name,
            group: $model->group,
        );
    }
}
