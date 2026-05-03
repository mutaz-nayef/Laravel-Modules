<?php

namespace Modules\Authorization\Application\Actions\Role;

use Modules\Authorization\Application\DTOs\Input\Role\RoleUpdateInputDto;
use Modules\Authorization\Application\DTOs\RoleOutputDto;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Entities\Role;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;

class RoleUpdateAction
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {

    }

    /**
     * @throws RoleNotFoundException
     */
    public function execute(RoleUpdateInputDto $input): RoleOutputDto
    {
        $role = $this->roleRepository->findById($input->roleId);
        if (!$role) {
            throw new RoleNotFoundException('RoleModel not found', 404);
        }
        $role = new Role(
            id: $input->roleId,
            name: $input->name,
            display_name: $input->display_name,
            permissions: $role->permissions(),
        );
        $updated = $this->roleRepository->save($role);

        return new RoleOutputDto(
            id: $updated->id(),
            name: $updated->name(),
            display_name: $updated->displayName(),
            permissions: $updated->permissions(),
        );
    }
}
