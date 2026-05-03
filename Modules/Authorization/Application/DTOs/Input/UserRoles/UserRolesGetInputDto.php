<?php

namespace Modules\Authorization\Application\DTOs\Input\UserRoles;

use Modules\Shared\Domain\ValueObjects\UserId;

class UserRolesGetInputDto
{

    public function __construct(
        public UserId $userId
    ) {
    }
}
