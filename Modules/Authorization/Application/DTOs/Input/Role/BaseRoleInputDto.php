<?php

namespace Modules\Authorization\Application\DTOs\Input\Role;

use Modules\Authorization\Domain\ValueObjects\RoleId;

class BaseRoleInputDto
{
    public function __construct(
        public RoleId $roleId,
    ) {
    }

}
