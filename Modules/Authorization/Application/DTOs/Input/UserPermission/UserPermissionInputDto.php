<?php

namespace Modules\Authorization\Application\DTOs\Input\UserPermission;


use Modules\Shared\Domain\ValueObjects\UserId;

class UserPermissionInputDto
{
    public function __construct(
        public UserId $userId,
        public array $permissions,
    ) {
    }
}
