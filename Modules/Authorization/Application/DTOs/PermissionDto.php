<?php

namespace Modules\Authorization\Application\DTOs;

use Modules\Authorization\Domain\ValueObjects\PermissionId;

class PermissionDto
{
    public function __construct(
        public PermissionId $id,
        public string $name,
        public string $group,
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new self(
            $data['id'],
            $data['name'],
            $data['group'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'group' => $this->group,
        ];
    }

}
