<?php

namespace Modules\Authorization\Application\DTOs\Input\UserPermission;


use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Shared\Domain\ValueObjects\UserId;

class UserDestroyPermissionInputDto
{
    public function __construct(
        public UserId $userId,
        public PermissionId $permissionId
        ,
    ) {
    }
}
