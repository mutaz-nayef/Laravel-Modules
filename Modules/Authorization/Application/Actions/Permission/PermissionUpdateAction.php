<?php

namespace Modules\Authorization\Application\Actions\Permission;

use Modules\Authorization\Application\DTOs\Input\Permission\PermissionUpdateInputDto;
use Modules\Authorization\Application\DTOs\Output\PermissionDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\Exceptions\PermissionNotFoundException;

class PermissionUpdateAction
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {

    }

    /**
     * @throws PermissionNotFoundException
     */
    public function execute(PermissionUpdateInputDto $input): PermissionDto
    {
        $permission = $this->permissionRepository->findById($input->permissionId);

        if (!$permission) {
            throw new PermissionNotFoundException('Permission not found', 404);
        }

        $permission = new Permission(
            id: $input->permissionId,
            name: $input->name,
            group: $input->group,
        );
        $updated = $this->permissionRepository->save($permission);

        return new PermissionDto(
            id: $updated->id(),
            name: $updated->name(),
            group: $updated->group(),
        );
    }
}
