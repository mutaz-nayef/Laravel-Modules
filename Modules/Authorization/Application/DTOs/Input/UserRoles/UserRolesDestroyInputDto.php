<?php

namespace Modules\Authorization\Application\DTOs\Input\UserRoles;

use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Shared\Domain\ValueObjects\UserId;

class UserRolesDestroyInputDto
{

    public function __construct(
        public UserId $userId,
        public RoleId $roleId,
    ) {
    }
}
