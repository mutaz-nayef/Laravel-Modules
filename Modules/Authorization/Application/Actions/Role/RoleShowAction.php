<?php

namespace Modules\Authorization\Application\Actions\Role;

use Modules\Authorization\Application\DTOs\Input\Role\BaseRoleInputDto;
use Modules\Authorization\Application\DTOs\Output\RoleOutputDto;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;

class RoleShowAction
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository
    ) {

    }


    /**
     * @throws RoleNotFoundException
     */
    public function execute(BaseRoleInputDto $input): RoleOutputDto
    {
        $role = $this->roleRepository->findById($input->roleId);
        if (!$role) {
            throw new RoleNotFoundException('Role not found', 404);
        }
        return new RoleOutputDto(
            id: $role->id(),
            name: $role->name(),
            display_name: $role->displayName(),
            permissions: $role->permissions(),
        );
    }
}
