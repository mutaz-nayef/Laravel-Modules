<?php

namespace Modules\Authorization\Application\DTOs;

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


    public static function fromArray(array $data): static
    {
        $permissions = array_map(
            fn(array $p) => PermissionDto::fromArray($p),
            $data['permissions'] ?? []
        );
        return new static(
            new RoleId($data['id']),
            $data['name'],
            $data['display_name'],
            $permissions,
        );
    }


}
