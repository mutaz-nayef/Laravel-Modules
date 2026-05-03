<?php

namespace Modules\Authorization\Infrastructure\Services;

use Modules\Authentication\Domain\ValueObjects\UserId;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Contracts\UserRoleInterface;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;
use Modules\Authorization\Domain\Exceptions\UserAlreadyHasRoleException;
use Modules\Authorization\Domain\Repositories\UserRoleRepositoryInterface;

class UserRoleService implements UserRoleInterface
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository,
        protected UserRoleRepositoryInterface $userRoleRepository,
    ) {
    }

    /**
     * @throws RoleNotFoundException
     * @throws UserAlreadyHasRoleException
     */
    public function syncRoles(UserId $userId, array $roles): void
    {
        foreach ($roles as $role) {
            $this->assignRole($userId, $role);
        }
    }

    /**
     * @throws RoleNotFoundException
     * @throws UserAlreadyHasRoleException
     * @throws UserAlreadyHasRoleException
     */
    public function assignRole(UserId $userId, string $roleAttr): void
    {
        $role = $this->roleRepository->findById($roleAttr);

        if (!$role) {
            $role = $this->roleRepository->findBySlug($roleAttr);
        }
        if (!$role) {
            throw new RoleNotFoundException('RoleModel not found');
        }

        if ($this->userRoleRepository->exists($userId, $role->id)) {
            throw new UserAlreadyHasRoleException(
                "User {$userId->value} already has role {$role->id}"
                , 409);
        }
        $this->userRoleRepository->create($userId, $role->id);
    }

    public function removeRole(UserId $userId, string $roleId): bool
    {
        return $this->userRoleRepository->delete($userId, $roleId);
    }

    public function hasAnyRole(UserId $userId, array $roles): bool
    {
        return array_any($roles, fn($role) => $this->hasRole($userId, $role));
    }

    public function hasRole(UserId $userId, string $roleId): bool
    {
        return $this->userRoleRepository->exists($userId, $roleId);
    }

    public function hasAllRoles(UserId $userId, array $roles): bool
    {
        return array_all($roles, fn($role) => $this->hasRole($userId, $role));
    }

    public function getRoleNames(UserId $userId): array
    {
        return $this->userRoleRepository->getRoleNames($userId);
    }
}
