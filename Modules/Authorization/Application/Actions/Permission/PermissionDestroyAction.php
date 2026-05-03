<?php

namespace Modules\Authorization\Application\Actions\Permission;


use Modules\Authorization\Application\DTOs\Input\Permission\BasePermissionInputDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\PermissionNotFoundException;

class PermissionDestroyAction
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {

    }

    /**
     * @throws PermissionNotFoundException
     */
    public function execute(BasePermissionInputDto $input): void
    {
        $permission = $this->permissionRepository->findById($input->permissionId);
        if (!$permission) {
            throw new PermissionNotFoundException('Permission not found', 404);
        }
        $this->permissionRepository->delete($input->permissionId);
    }
}
