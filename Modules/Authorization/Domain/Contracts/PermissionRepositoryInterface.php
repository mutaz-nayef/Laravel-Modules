<?php

namespace Modules\Authorization\Domain\Contracts;

use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Shared\Domain\ValueObjects\UserId;

interface PermissionRepositoryInterface
{

    public function findByUserId(UserId $userId): ?array;

    public function findById(PermissionId $permissionId);

    public function findByName(string $name): ?Permission;

    /**
     * @return Permission[]|null
     */
    public function getAll(): ?array;

    public function save(Permission $permission): ?Permission;

    public function delete(PermissionId $permissionId): void;

//    public function exists(Role $role, $): bool;

}

