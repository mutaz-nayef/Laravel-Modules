<?php

namespace Modules\Authorization\Infrastructure\Services;

use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Contracts\RolePermissionInterface;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\PermissionNotFoundException;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;
use Modules\Authorization\Domain\Repositories\RolePermissionRepositoryInterface;

class RolePermissionService implements RolePermissionInterface
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository,
        protected PermissionRepositoryInterface $permissionRepository,
        protected RolePermissionRepositoryInterface $rolePermissionRepository,
    ) {
    }

    /**
     * @throws RoleNotFoundException
     * @throws PermissionNotFoundException
     */
    public function givePermissionTo(string $role_id, array $permissions): array
    {
        $handled = $this->handelRolePermissions($role_id, $permissions);

        $data = $this->normalizeDataToInsert($handled->role->id, $handled->permissions);

        $added = $this->rolePermissionRepository->create($data);

        return [
            'message' => $added ? 'PermissionModel added successfully.' : 'RoleModel already has PermissionModel!',
        ];
    }

    /**
     * @throws RoleNotFoundException
     * @throws PermissionNotFoundException
     */
    protected function handelRolePermissions(string $role_id, array $permissions): object
    {
        $role = $this->findByIdOrFail($role_id);

        $permissionsIds = $this->permissionRepository->getPermissionsIdsBySlug($permissions);

        if (!$permissionsIds) {
            throw new PermissionNotFoundException('Permissions not found', 404);
        }
        return (object) [
            'role' => $role,
            'permissions' => $permissionsIds,
        ];
    }

    /**
     * @throws RoleNotFoundException
     */
    protected function findByIdOrFail(string $role_id)
    {

        $role = $this->roleRepository->findById($role_id);

        if (!$role) {
            throw new RoleNotFoundException('RoleModel not found', 404);
        }

        return $role;
    }

    protected function normalizeDataToInsert($roleId, array $permissionsIds): array
    {
        $data = [];
        $now = now();
        foreach ($permissionsIds as $permissionId) {
            $data[] = [
                'role_id' => $roleId,
                'permission_id' => $permissionId,
                'created_at' => $now
            ];
        }
        return $data;
    }

    /**
     * @throws RoleNotFoundException
     * @throws PermissionNotFoundException
     */
    public function revokePermissionTo(string $role_id, array $permissions): array
    {
        $handled = $this->handelRolePermissions($role_id, $permissions);

        $revoked = $this->rolePermissionRepository->revoke($handled->role->id, $handled->permissions);

        return [
            'message' => $revoked ? 'Permissions revoked successfully.' : 'PermissionModel already revoked!',
        ];
    }

    /**
     * @throws RoleNotFoundException
     * @throws PermissionNotFoundException
     */
    public function deletePermission(string $role_id, string $permissions): array
    {
        $role = $this->findByIdOrFail($role_id);
        $permission = $this->permissionRepository->findById($permissions);

        if (!$permission) {
            throw new PermissionNotFoundException('Permissions not found', 404);
        }

        $deleted = $this->rolePermissionRepository->delete($role->id, $permission->id);
        return [
            'message' => $deleted ? 'Permissions deleted successfully.' : 'PermissionModel already deleted!',
        ];
    }

    /**
     * @throws RoleNotFoundException
     * @throws PermissionNotFoundException
     */
    public function syncPermissions(string $role_id, array $permissions): array
    {
        $handled = $this->handelRolePermissions($role_id, $permissions);

        $synced = $this->rolePermissionRepository->sync($handled->role, $handled->permissions);
        return [
            'attached' => count($synced['attached']),
            'detached' => count($synced['detached']),
            'updated' => count($synced['updated'])
        ];
    }

    /**
     * @throws RoleNotFoundException
     * @throws PermissionNotFoundException
     */
    public function hasAnyPermission(string $role_id, array $permissions): bool
    {
        $handled = $this->handelRolePermissions($role_id, $permissions);
        return $this->rolePermissionRepository->hasAnyPermission($handled->role->id, $handled->permissions);
    }

    /**
     * @throws RoleNotFoundException
     * @throws PermissionNotFoundException
     */
    public function hasPermissionTo(string $role_id, string $permission): bool
    {
        $role = $this->findByIdOrFail($role_id);
        $permission = $this->getPermissionOrFail($permission);
        return $this->rolePermissionRepository->exists($role->id, $permission->id);
    }

    /**
     * @throws PermissionNotFoundException
     */
    protected function getPermissionOrFail($perm)
    {
        $permission = $this->permissionRepository->findById($perm);

        if (!$permission) {
            $permission = $this->permissionRepository->findBySlug($perm);
        }
        if (!$permission) {
            throw new PermissionNotFoundException('PermissionModel not found');
        }

        return $permission;
    }
}
