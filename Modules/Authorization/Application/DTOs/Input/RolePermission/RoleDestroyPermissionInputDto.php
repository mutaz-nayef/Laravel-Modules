<?php

namespace Modules\Authorization\Application\DTOs\Input\RolePermission;


use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Authorization\Domain\ValueObjects\RoleId;

class RoleDestroyPermissionInputDto
{
    public function __construct(
        public RoleId $role,
        public PermissionId $permission,
    ) {
    }
}
