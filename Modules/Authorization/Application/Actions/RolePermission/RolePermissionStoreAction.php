<?php

namespace Modules\Authorization\Application\Actions\RolePermission;

use Modules\Authorization\Application\DTOs\Input\RolePermission\RolePermissionInputDto;
use Modules\Authorization\Application\DTOs\Output\RoleOutputDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\PermissionNotFoundException;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;
use Modules\Shared\Abstractions\EventDispatcherInterface;

class RolePermissionStoreAction
{
    public function __construct(
        protected readonly RoleRepositoryInterface $roleRepository,
        protected readonly PermissionRepositoryInterface $permissionRepository,
        protected readonly EventDispatcherInterface $eventDispatcher
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
            throw new RoleNotFoundException('Role not found', 404);
        }

        $permissions = array_map(fn($permission) => $this->permissionRepository->findByName($permission),
            $input->permissions);
        if (empty($permissions)) {
            throw new PermissionNotFoundException('Permission not found', 404);
        }
        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }
        $this->eventDispatcher->dispatchAll($role->pullDomainEvents());
        $role = $this->roleRepository->savePermissions($role);

        return new RoleOutputDto(
            id: $role->id(),
            name: $role->name(),
            display_name: $role->displayName(),
            permissions: $role->permissions()
        );
    }
}
