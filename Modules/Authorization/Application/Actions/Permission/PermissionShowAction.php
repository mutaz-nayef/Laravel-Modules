<?php

namespace Modules\Authorization\Application\Actions\Permission;

use Modules\Authorization\Application\DTOs\PermissionDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\PermissionNotFoundException;

class PermissionShowAction
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {

    }

    /**
     * @throws PermissionNotFoundException
     */
    public function execute($permission): PermissionDto
    {
        $permission = $this->permissionRepository->getPermissionsWithRole($permission);
        if (!$permission) {
            throw new PermissionNotFoundException('PermissionModel not found', 404);
        }
        return PermissionDto::fromArray($permission->toArray());
    }
}
