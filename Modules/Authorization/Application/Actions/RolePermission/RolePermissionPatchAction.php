<?php

namespace Modules\Authorization\Application\Actions\RolePermission;

use Modules\Authorization\Application\DTOs\Input\RolePermission\RolePermissionPatchInputDto;
use Modules\Authorization\Application\DTOs\Output\RoleOutputDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\PermissionNotFoundException;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;

class RolePermissionPatchAction
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
    public function execute(RolePermissionPatchInputDto $input): RoleOutputDto
    {
        $role = $this->roleRepository->findById($input->roleId);
        if (!$role) {
            throw new RoleNotFoundException('Role not found');
        }

        $addPermissions = array_unique(array_map(fn($permission
        ) => $this->permissionRepository->findByName($permission), $input->add));

        $removePermissions = array_unique(array_map(fn($permission
        ) => $this->permissionRepository->findByName($permission), $input->remove));

        foreach ($addPermissions as $permission) {

            $role->givePermissionTo($permission);
        }
        foreach ($removePermissions as $permission) {
            $role->revokePermissionTo($permission);
        }

        $this->roleRepository->savePermissions($role);

        return new RoleOutputDto(
            id: $role->id(),
            name: $role->name(),
            display_name: $role->displayName(),
            permissions: $role->permissions()
        );
    }
}
