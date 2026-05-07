<?php

namespace Modules\Authorization\Infrastructure\Mappers;

use Illuminate\Support\Collection;
use Modules\Authorization\Domain\Entities\Role;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Authorization\Infrastructure\Models\RoleModel;

class RoleMapper
{


    public static function toDomainEntity(RoleModel|Collection $model): Role|array
    {
        if ($model instanceof Collection) {
            return $model
                ->map(fn(RoleModel $role) => self::toDomainEntity($role))
                ->all();
        }
        return new Role(
            id: new RoleId($model->id),
            name: $model->name,
            display_name: $model->display_name,
            permissions: $model->permissions
                ->map(fn($permission) => PermissionMapper::toDomainEntity($permission))
                ->all(),
        );
    }
}
