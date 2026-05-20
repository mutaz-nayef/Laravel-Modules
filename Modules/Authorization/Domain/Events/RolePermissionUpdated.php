<?php

namespace Modules\Authorization\Domain\Events;

use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Shared\Domain\Events\BaseDomainEvent;

class RolePermissionUpdated extends BaseDomainEvent
{

    public function __construct(
        public readonly RoleId $roleId,
        public readonly PermissionId $permissionId
    ) {
        parent::__construct();
    }

    public function aggregateId(): string
    {
        return (string) $this->roleId->value();
    }
}
