<?php

namespace Modules\Authorization\Application\Actions\UserRole;

use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\UserNotFoundException;
use Modules\Authorization\Application\DTOs\Input\UserRoles\UserRolesGetInputDto;
use Modules\Authorization\Application\DTOs\Output\UserOutputDto;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;

class UserRoleGetAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository,
    ) {

    }


    /**
     * @throws UserNotFoundException
     */
    public function execute(UserRolesGetInputDto $input): UserOutputDto
    {
        $user = $this->userRepository->findById($input->userId);

        if (!$user) {
            throw new UserNotFoundException('User not found.', 404);
        }

        $roles = $this->roleRepository->findByUserId($user->id());


        if ($roles) {
            foreach ($roles as $role) {
                $user->assignRole($role);
            }
        }

        return new UserOutputDto(
            id: $user->id(),
            name: $user->name(),
            email: $user->email(),
            roles: $user->roles(),
            permissions: $user->permissions(),
        );
    }
}
