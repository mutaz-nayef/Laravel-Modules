<?php

namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Application\DTOs\Auth\Input\AuthUserInputDto;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\UnAuthenticatedException;
use Modules\Authorization\Application\DTOs\Output\UserOutputDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;


class AuthUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PermissionRepositoryInterface $permissionRepository,
        private readonly RoleRepositoryInterface $roleRepository,
    ) {
    }

    /**
     * @throws UnAuthenticatedException
     */
    public function execute(AuthUserInputDto $input): UserOutputDto
    {
        $user = $this->userRepository->findById($input->userId);
        if (!$user) {
            throw new UnAuthenticatedException('Unauthenticated', 401);
        }
        $roles = $this->roleRepository->findByUserId($user->id());
        if ($roles) {
            foreach ($roles as $role) {
                $user->assignRole($role);
            }
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
