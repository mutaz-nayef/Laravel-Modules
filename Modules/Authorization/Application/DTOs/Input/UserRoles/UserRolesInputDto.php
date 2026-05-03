<?php

namespace Modules\Authorization\Application\DTOs\Input\UserRoles;


use Modules\Shared\Domain\ValueObjects\UserId;

class UserRolesInputDto
{
    public function __construct(
        public UserId $userId,
        public array $roles,
    ) {
    }
}
