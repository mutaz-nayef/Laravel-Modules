<?php

namespace Modules\Authorization\Application\Actions\UserRole;

use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\UserNotFoundException;
use Modules\Authorization\Application\DTOs\Input\UserRoles\UserRolesInputDto;
use Modules\Authorization\Application\DTOs\Output\UserOutputDto;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;

//use Modules\Authorization\Domain\Services\Domain\Contracts\RolePermissionInterface;

class UserRoleSyncAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository
    ) {

    }

    /**
     * @throws UserNotFoundException
     * @throws RoleNotFoundException
     */
    public function execute(UserRolesInputDto $input): UserOutputDto
    {
        $user = $this->userRepository->findById($input->userId);
        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }
        $roles = array_map(fn($role) => $this->roleRepository->findByName($role),
            $input->roles);

        if (empty($roles)) {
            throw new RoleNotFoundException('Role not found', 404);
        }
        $user->syncRoles($roles);
        $user = $this->userRepository->saveRoles($user);

        return new UserOutputDto(
            id: $user->id(),
            name: $user->name(),
            email: $user->email(),
            roles: $user->roles(),
            permissions: $user->permissions(),
        );
    }
}
