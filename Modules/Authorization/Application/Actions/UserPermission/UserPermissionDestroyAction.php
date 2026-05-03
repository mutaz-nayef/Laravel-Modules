<?php

namespace Modules\Authorization\Application\Actions\UserPermission;

use InvalidArgumentException;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\UserNotFoundException;
use Modules\Authorization\Application\DTOs\Input\UserPermission\UserDestroyPermissionInputDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\PermissionNotFoundException;

class UserPermissionDestroyAction
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected PermissionRepositoryInterface $permissionRepository
    ) {

    }

    /**
     * @throws PermissionNotFoundException
     * @throws UserNotFoundException
     */
    public function execute(UserDestroyPermissionInputDto $input): void
    {
        $user = $this->userRepository->findById($input->userId);
        if (!$user) {
            throw new UserNotFoundException('User not found', 404);
        }
        $permission = $this->permissionRepository->findById($input->permissionId);

        if (!$permission) {
            throw new PermissionNotFoundException('Permission not found', 404);
        }


        if (!$user->hasPermissionTo($permission)) {
            throw new InvalidArgumentException('User does not has permission', 404);
        }

        $user->revokePermissionTo($permission);
        $this->userRepository->savePermissions($user);

    }
}
