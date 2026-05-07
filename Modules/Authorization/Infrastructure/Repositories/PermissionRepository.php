<?php

namespace Modules\Authorization\Infrastructure\Repositories;

use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Authorization\Domain\ValueObjects\PolicyConditions;
use Modules\Authorization\Infrastructure\Mappers\PermissionMapper;
use Modules\Authorization\Infrastructure\Models\PermissionModel;
use Modules\Shared\Domain\ValueObjects\UserId;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function __construct(private readonly PermissionModel $model)
    {
    }

    public function findForUser(int $userId, string $permissionName): ?Permission
    {
        // 1. Check direct permission first
        $direct = $this->model::where('permissions.name', $permissionName)
            ->join('user_permissions', 'user_permissions.permission_id', '=', 'permissions.id')
            ->where('user_permissions.user_id', $userId)
            ->select('permissions.*', 'user_permissions.conditions')
            ->first();

        if ($direct) {
            return new Permission(
                new PermissionId($direct->id),
                $direct->name,
                $direct->group,
                PolicyConditions::fromJson($direct->conditions),
            );
        }
        // 2. If user don't have this permission as direct permission, check it via his role
        $model = PermissionModel::where('permissions.name', $permissionName)
            ->join('role_permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->join('roles', 'role_permissions.role_id', '=', 'roles.id')
            ->join('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->where('user_roles.user_id', $userId)
            ->select('permissions.*', 'role_permissions.conditions')
            ->first();

        if (!$model) {
            return null;
        }
        return new Permission(
            new PermissionId($model->id),
            $model->name,
            $model->group,
            PolicyConditions::fromJson($model->conditions),
        );
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
        $permission->delete();
    }
}
