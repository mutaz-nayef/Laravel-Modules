<?php

namespace Modules\Authorization\Application\DTOs\Input\Permission;

use Modules\Authorization\Domain\ValueObjects\PermissionId;

class PermissionUpdateInputDto
{
    public function __construct(
        public PermissionId $permissionId,
        public string $name,
        public string $group,
    ) {
    }
}
