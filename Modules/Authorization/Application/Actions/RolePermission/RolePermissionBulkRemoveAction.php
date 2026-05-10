<?php

namespace Modules\Authorization\Application\Actions\RolePermission;

use InvalidArgumentException;
use Modules\Authorization\Application\DTOs\Input\RolePermission\RolePermissionInputDto;
use Modules\Authorization\Application\DTOs\Output\RoleOutputDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\PermissionNotFoundException;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;

class RolePermissionBulkRemoveAction
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
    public function execute(RolePermissionInputDto $input): RoleOutputDto
    {
        $role = $this->roleRepository->findById($input->role);
        if (!$role) {
            throw new RoleNotFoundException('Role not found');
        }

        $removePermissions = (array_map(fn($permission
        ) => $this->permissionRepository->findByName($permission), $input->permissions));

        if (empty($removePermissions)) {
            throw new PermissionNotFoundException("Permission not found", 404);
        }


        foreach ($removePermissions as $permission) {
            if (!$role->hasPermissionTo($permission)) {
                throw new InvalidArgumentException("Role does not has {$permission->name()} permission", 404);
            }
            $role->revokePermissionTo($permission);
        }

        $this->roleRepository->savePermissions($role);

        return new RoleOutputDto(
            id: $role->id(),
            name: $role->name(),
            display_name: $role->displayName(),
            permissions: $role->permissions(),
        );
    }
}
