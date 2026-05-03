<?php

namespace Modules\Authorization\Application\DTOs\Input\Role;

class RoleStoreInputDto
{
    public function __construct(
        public string $name,
        public string $display_name,
    ) {
    }
}
