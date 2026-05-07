<?php

namespace Modules\Authorization\Domain\Entities;

use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Authorization\Domain\ValueObjects\PolicyConditions;

final class Permission
{

    public function __construct(
        private readonly ?PermissionId $id,
        private readonly string $name,
        private readonly string $group,
        private readonly ?PolicyConditions $conditions = null,
    ) {
    }


    //Getters
    public function name(): string
    {
        return $this->name;
    }

    public function group(): string
    {
        return $this->group;
    }

    public function id(): ?PermissionId
    {
        return $this->id;
    }

    public function conditions(): PolicyConditions
    {
        return $this->conditions;
    }

}
