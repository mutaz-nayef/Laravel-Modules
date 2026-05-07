<?php

namespace Modules\Authorization\Application\Actions\Permission;

use Modules\Authorization\Application\DTOs\Input\Permission\BasePermissionInputDto;
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
    public function execute(BasePermissionInputDto $input): PermissionDto
    {
        $permission = $this->permissionRepository->findById($input->permissionId);
        if (!$permission) {
            throw new PermissionNotFoundException('PermissionModel not found', 404);
        }
        return new PermissionDto(
            id: $permission->id(),
            name: $permission->name(),
            group: $permission->group(),
        );
    }
}
