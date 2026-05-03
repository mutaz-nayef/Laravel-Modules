<?php

namespace Modules\Authorization\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Entities\RolePermission;
use Modules\Authorization\Domain\Repositories\RolePermissionRepositoryInterface;

class RolePermissionRepository implements RolePermissionRepositoryInterface
{
    public function __construct(protected RoleRepositoryInterface $roleRepository)
    {
    }

    public function getRoleWithPermissions($role)
    {
        $data = $this->roleRepository->findById($role);

        return $data->load('permissions')->toArray();
    }

    public function create(array $data): bool
    {
        return DB::table('role_has_permissions')->insertOrIgnore($data);
    }

    public function save(RolePermission $rolePermission): void
    {
        // TODO: Implement save() method.
    }

    public function revoke(string $roleId, array $permissions): bool
    {
        return DB::table('role_has_permissions')
            ->where('role_id', $roleId)
            ->whereIn('permission_id', $permissions)
            ->delete();
    }

    public function delete(string $roleId, string $permission): bool
    {
        return DB::table('role_has_permissions')
                ->where('role_id', $roleId)
                ->where('permission_id', $permission)
                ->delete() > 0;
    }

    public function sync($role, array $permissionsId): array
    {
        return $role->permissions()->sync($permissionsId);
    }

    public function hasAnyPermission(string $roleId, array $permissionsId): bool
    {
//        dd(Hash::check('admin123', '$2y$12$1MfEwUVxGwSFBODhPD/kq.FMSji4FtV9zsq8pWjBnoiXzimV9oNaW'));
        return DB::table('role_has_permissions')
            ->where('role_id', $roleId)
            ->whereIn('permission_id', $permissionsId)
            ->exists();
    }

    public function exists(string $roleId, string $permissionId): bool
    {
        return DB::table('role_has_permissions')
            ->where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->exists();
    }

    public function getPermissions(string $roleId): array
    {
        return DB::table('role_has_permissions')
            ->where('role_id', $roleId)
            ->pluck('permission_id')
            ->toArray();
    }

}
