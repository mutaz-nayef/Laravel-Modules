<?php

namespace Modules\Authorization\Application\DTOs\Input\Permission;

use Modules\Authorization\Domain\ValueObjects\PermissionId;

class BasePermissionInputDto
{
    public function __construct(
        public PermissionId $permissionId,
    ) {
    }

}
