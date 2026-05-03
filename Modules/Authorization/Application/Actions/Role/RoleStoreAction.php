<?php

namespace Modules\Authorization\Application\Actions\Role;

use Modules\Authorization\Application\DTOs\Input\Role\RoleStoreInputDto;
use Modules\Authorization\Application\DTOs\RoleOutputDto;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Entities\Role;

class RoleStoreAction
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {

    }

    public function execute(RoleStoreInputDto $input): RoleOutputDto
    {
        $role = new Role(
            id: null,
            name: $input->name,
            display_name: $input->display_name,
            permissions: []
        );

        $role = $this->roleRepository->save($role);

        return new RoleOutputDto(
            id: $role->id(),
            name: $role->name(),
            display_name: $role->displayName(),
            permissions: $role->permissions()
        );
    }
}
