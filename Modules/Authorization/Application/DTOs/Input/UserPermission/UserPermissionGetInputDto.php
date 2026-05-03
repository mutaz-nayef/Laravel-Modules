<?php

namespace Modules\Authorization\Application\DTOs\Input\UserPermission;


use Modules\Shared\Domain\ValueObjects\UserId;

class UserPermissionGetInputDto
{
    public function __construct(
        public UserId $userId,
    ) {
    }
}
