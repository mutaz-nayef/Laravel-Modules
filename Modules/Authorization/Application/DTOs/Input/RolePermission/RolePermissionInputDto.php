<?php

namespace Modules\Authorization\Application\DTOs\Input\RolePermission;


use Modules\Authorization\Domain\ValueObjects\RoleId;

class RolePermissionInputDto
{
    public function __construct(
        public RoleId $role,
        public array $permissions,
    ) {
    }
}
