<?php

namespace Modules\Authorization\Application\Actions\Permission;

use Modules\Authorization\Application\DTOs\Input\Permission\PermissionStoreInputDto;
use Modules\Authorization\Application\DTOs\Output\PermissionDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Entities\Permission;

class PermissionStoreAction
{
    public function __construct(
        protected PermissionRepositoryInterface $permissionRepository
    ) {

    }

    public function execute(PermissionStoreInputDto $input): PermissionDto
    {

        $permission = new Permission(
            id: null,
            name: $input->name,
            group: $input->group,
        );

        $permission = $this->permissionRepository->save($permission);

        return new PermissionDto(
            id: $permission->id(),
            name: $permission->name(),
            group: $permission->group(),
        );
    }
}
