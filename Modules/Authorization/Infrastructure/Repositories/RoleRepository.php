<?php

namespace Modules\Authorization\Infrastructure\Repositories;

use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Entities\Role;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Authorization\Infrastructure\Mappers\RoleMapper;
use Modules\Authorization\Infrastructure\Models\RoleModel;
use Modules\Shared\Domain\ValueObjects\UserId;

class RoleRepository implements RoleRepositoryInterface
{

    public function findByUserId(UserId $userId): ?array
    {
        $models = RoleModel::whereHas(
            'users',
            fn($query) => $query->where('users.id', $userId->value())
        )
            ->with('permissions')
            ->get();
        if (!$models) {
            return null;
        }
        return $models->map(
            fn($m) => RoleMapper::toDomainEntity($m)
        )->all();
    }

    public function findByName(string $name): ?Role
    {
        $model = RoleModel::where('name', $name)->first();
        return $model ? RoleMapper::toDomainEntity($model) : null;
    }

    public function getAll(): ?array
    {
        return RoleModel::all()
            ->map(fn(RoleModel $model) => RoleMapper::toDomainEntity($model))->toArray() ?? null;
    }

    public function save(Role $role): ?Role
    {
        $model = RoleModel::updateOrCreate(
            [
                'id' => $role->id()?->value(),
            ],
            [
                'name' => $role->name(),
                'display_name' => $role->displayName(),
            ]
        );
        return $model ? RoleMapper::toDomainEntity($model->refresh()) : null;
    }

    public function savePermissions(Role $role): ?Role
    {
        $model = RoleModel::findOrFail($role->id()->value());

        $model->permissions()->sync(array_map(fn($p) => $p->id()->value(), $role->permissions()));

        return $model ? RoleMapper::toDomainEntity($model->refresh()) : null;
    }

    public function findById(RoleId $roleId): ?Role
    {
        $model = RoleModel::find($roleId->value());
        return $model ? RoleMapper::toDomainEntity($model) : null;
    }

    public function delete(RoleId $roleId): void
    {
        $role = RoleModel::findOrfail($roleId->value());
        $role->delete();
    }
}
