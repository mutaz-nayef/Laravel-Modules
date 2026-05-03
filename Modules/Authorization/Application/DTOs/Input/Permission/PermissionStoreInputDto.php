<?php

namespace Modules\Authorization\Application\DTOs\Input\Permission;

class PermissionStoreInputDto
{
    public function __construct(
        public string $name,
        public string $group,
    ) {
    }
}
