<?php

namespace Modules\Authorization\Application\Actions\RolePermission;

use InvalidArgumentException;
use Modules\Authorization\Application\DTOs\Input\RolePermission\RoleDestroyPermissionInputDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\PermissionNotFoundException;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;

class RolePermissionDestroyAction
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository,
        protected PermissionRepositoryInterface $permissionRepository
    ) {

    }

    /**
     * @throws RoleNotFoundException
     * @throws PermissionNotFoundException
     */
    public function execute(RoleDestroyPermissionInputDto $input): void
    {
        $role = $this->roleRepository->findById($input->role);
        if (!$role) {
            throw new RoleNotFoundException('Role not found', 404);
        }
        $permission = $this->permissionRepository->findById($input->permission);

        if (!$permission) {
            throw new PermissionNotFoundException('Permission not found', 404);
        }


        if (!$role->hasPermissionTo($permission)) {
            throw new InvalidArgumentException('Role does not has permission', 404);
        }

        $role->revokePermissionTo($permission);
        $this->roleRepository->savePermissions($role);
    }
}
