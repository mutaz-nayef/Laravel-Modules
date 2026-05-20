<?php

namespace Modules\Authorization\Domain\Contracts;

use Modules\Authorization\Domain\Entities\Role;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Shared\Domain\ValueObjects\UserId;

interface RoleRepositoryInterface
{

    public function findByUserId(UserId $userId): ?array;

    public function findById(RoleId $roleId);

    public function findByName(string $name);

    /**
     * @return Role[]|null
     */
    public function getAll(): ?array;

    public function save(Role $role): ?Role;

    public function savePermissions(Role $role): ?Role;

    public function delete(RoleId $roleId): void;


}
