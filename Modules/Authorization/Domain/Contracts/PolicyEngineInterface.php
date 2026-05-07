<?php

namespace Modules\Authorization\Domain\Contracts;

use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\ValueObjects\FieldPermissions;
use Modules\Authorization\Domain\ValueObjects\ResourceAttributes;
use Modules\Shared\Domain\ValueObjects\UserId;

interface PolicyEngineInterface
{
    public function evaluate(
        UserId $userId,
        Permission $permission,
        ResourceAttributes $resource,
    ): bool;

    public function resolveFieldPermissions(UserId $userId, string $permissionName): FieldPermissions;
}
