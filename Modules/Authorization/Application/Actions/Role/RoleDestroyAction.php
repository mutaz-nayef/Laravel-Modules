<?php

namespace Modules\Authorization\Application\Actions\Role;

use Modules\Authorization\Application\DTOs\Input\Role\BaseRoleInputDto;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;

class RoleDestroyAction
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {

    }


    /**
     * @throws RoleNotFoundException
     */
    public function execute(BaseRoleInputDto $input): void
    {
        $role = $this->roleRepository->findById($input->roleId);
        if (!$role) {
            throw new RoleNotFoundException('Role not found', 404);
        }

        $this->roleRepository->delete($input->roleId);

    }
}
