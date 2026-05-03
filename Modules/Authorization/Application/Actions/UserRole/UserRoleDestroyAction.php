<?php

namespace Modules\Authorization\Application\Actions\UserRole;

use InvalidArgumentException;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\UserNotFoundException;
use Modules\Authorization\Application\DTOs\Input\UserRoles\UserRolesDestroyInputDto;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;

class UserRoleDestroyAction
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {

    }

    /**
     * @throws UserNotFoundException
     * @throws RoleNotFoundException
     */
    public function execute(UserRolesDestroyInputDto $input): void
    {
        $user = $this->userRepository->findById($input->userId);
        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }
        $role = $this->roleRepository->findById($input->roleId);

        if (!$role) {
            throw new RoleNotFoundException('Role not found', 404);
        }
        if (!$user->hasRole($role)) {
            throw new InvalidArgumentException('User does not have this role.', 404);
        }

        $user->revokeRole($role);

        $this->userRepository->saveRoles($user);
    }
}
