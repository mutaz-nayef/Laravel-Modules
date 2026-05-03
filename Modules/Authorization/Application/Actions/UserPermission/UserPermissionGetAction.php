<?php

namespace Modules\Authorization\Application\Actions\UserPermission;

use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\UserNotFoundException;
use Modules\Authorization\Application\DTOs\Input\UserPermission\UserPermissionGetInputDto;
use Modules\Authorization\Application\DTOs\Output\UserOutputDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;

class UserPermissionGetAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PermissionRepositoryInterface $permissionRepository
    ) {

    }

    /**
     * @throws UserNotFoundException
     */
    public function execute(UserPermissionGetInputDto $input): UserOutputDto
    {
        $user = $this->userRepository->findById($input->userId);
        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }

        $permissions = $this->permissionRepository->findByUserId($input->userId);
        if ($permissions) {
            foreach ($permissions as $permission) {
                $user->givePermissionTo($permission);
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
