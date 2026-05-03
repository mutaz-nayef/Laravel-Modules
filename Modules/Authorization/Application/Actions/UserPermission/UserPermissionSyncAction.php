<?php

namespace Modules\Authorization\Application\Actions\UserPermission;

use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\UserNotFoundException;
use Modules\Authorization\Application\DTOs\Input\UserPermission\UserPermissionInputDto;
use Modules\Authorization\Application\DTOs\Output\UserOutputDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\PermissionNotFoundException;

class UserPermissionSyncAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PermissionRepositoryInterface $permissionRepository,
    ) {

    }

    /**
     * @throws PermissionNotFoundException
     * @throws UserNotFoundException
     */
    public function execute(UserPermissionInputDto $input): UserOutputDto
    {
        $user = $this->userRepository->findById($input->userId);
        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }

        $permissions = array_map(fn($permission) => $this->permissionRepository->findByName($permission),
            $input->permissions);
        if (empty($permissions)) {
            throw new PermissionNotFoundException('Permission not found', 404);
        }

        $user->syncPermissions($permissions);

        $user = $this->userRepository->savePermissions($user);

        return new UserOutputDto(
            id: $user->id(),
            name: $user->name(),
            email: $user->email(),
            roles: $user->roles(),
            permissions: $user->permissions(),
        );
    }
}
