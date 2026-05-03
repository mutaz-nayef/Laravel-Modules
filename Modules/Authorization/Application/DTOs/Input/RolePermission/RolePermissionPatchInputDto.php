<?php

namespace Modules\Authorization\Application\DTOs\Input\RolePermission;


use Modules\Authorization\Domain\ValueObjects\RoleId;

class RolePermissionPatchInputDto
{
    public function __construct(
        public RoleId $roleId,
        public array $add,
        public array $remove,
    ) {
    }
}
