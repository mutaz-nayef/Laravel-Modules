<?php

namespace Modules\Authorization\Application\DTOs\Input;

use Modules\Authorization\Domain\ValueObjects\ResourceAttributes;
use Modules\Shared\Domain\ValueObjects\UserId;

class CheckPermissionInputDto
{
    public function __construct(
        public UserId $userId,
        public string $permissionName,
        public ResourceAttributes $resource,
    ) {
    }
}
