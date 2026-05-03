<?php

namespace Modules\Authorization\Application\DTOs\Input\Role;

use Modules\Authorization\Domain\ValueObjects\RoleId;

class RoleUpdateInputDto
{
    public function __construct(
        public RoleId $roleId,
        public string $name,
        public string $display_name,

    ) {
    }

}
