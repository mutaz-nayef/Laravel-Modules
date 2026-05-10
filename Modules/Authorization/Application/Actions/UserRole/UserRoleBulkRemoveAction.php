<?php

namespace Modules\Authorization\Application\Actions\UserRole;

use InvalidArgumentException;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\UserNotFoundException;
use Modules\Authorization\Application\DTOs\Input\UserRoles\UserRolesInputDto;
use Modules\Authorization\Application\DTOs\Output\UserOutputDto;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;

class UserRoleBulkRemoveAction
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected RoleRepositoryInterface $roleRepository
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

        $removeRoles = (array_map(fn($role
        ) => $this->roleRepository->findByName($role), $input->roles));

        if (empty($removeRoles)) {
            throw new RoleNotFoundException("Role not found", 404);
        }


        foreach ($removeRoles as $role) {
            if (!$user->hasRole($role)) {
                throw new InvalidArgumentException("User does not has {$role->name()} role", 404);
            }
            $user->revokeRole($role);
        }

        $this->userRepository->saveRoles($user);

        return new UserOutputDto(
            id: $user->id(),
            name: $user->name(),
            email: $user->email(),
            roles: $user->roles(),
            permissions: $user->permissions(),
        );
    }
}
