<?php

namespace Modules\Authorization\Infrastructure\Repositories;

use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Authorization\Infrastructure\Mappers\PermissionMapper;
use Modules\Authorization\Infrastructure\Models\PermissionModel;
use Modules\Shared\Domain\ValueObjects\UserId;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function __construct(private readonly PermissionModel $model)
    {
    }

    public function findByUserId(UserId $userId): ?array
    {
        $models = $this->model::whereHas(
            'users',
            fn($query) => $query->where('users.id', $userId->value())
        )->get();

        if (!$models) {
            return null;
        }
        return $models->map(
            fn($m) => PermissionMapper::toDomainEntity($m)
        )->all();
    }


    public function findById(PermissionId $permissionId): ?Permission
    {
        $model = $this->model::find($permissionId->value());

        return $model ? PermissionMapper::toDomainEntity($model) : null;
    }

    public function findByName(string $name): ?Permission
    {
        $model = $this->model::where('name', $name)->first();

        return $model ? PermissionMapper::toDomainEntity($model) : null;
    }

    public function getAll(): ?array
    {
        return $this->model::all()
            ->map(fn($model) => PermissionMapper::toDomainEntity($model))->toArray() ?? null;
    }

    public function save(Permission $permission): ?Permission
    {

        $model = $this->model::updateOrCreate(
            [
                'id' => $permission->id()?->value(),
            ],
            [
                'name' => $permission->name(),
                'group' => $permission->group(),
            ]
        );

        return $model ? PermissionMapper::toDomainEntity($model->refresh()) : null;

    }

    public function delete(PermissionId $permissionId): void
    {
        $permission = $this->model::findOrfail($permissionId->value());
        dd($permission);
        $permission->delete();
    }
}
