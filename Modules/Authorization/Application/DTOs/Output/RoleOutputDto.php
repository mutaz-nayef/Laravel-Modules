<?php

namespace Modules\Authorization\Application\DTOs\Output;

use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\ValueObjects\RoleId;

class RoleOutputDto
{
    /**
     * @param  Permission[]  $permissions
     */
    public function __construct(
        public RoleId $id,
        public string $name,
        public string $display_name,
        public array $permissions,
    ) {
    }

}
