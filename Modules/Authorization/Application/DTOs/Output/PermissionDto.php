<?php

namespace Modules\Authorization\Application\DTOs\Output;

use Modules\Authorization\Domain\ValueObjects\PermissionId;

class PermissionDto
{
    public function __construct(
        public PermissionId $id,
        public string $name,
        public string $group,
    ) {
    }


}
