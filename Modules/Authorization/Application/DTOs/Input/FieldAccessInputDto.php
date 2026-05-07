<?php

namespace Modules\Authorization\Application\DTOs\Input;

use Modules\Shared\Domain\ValueObjects\UserId;

class FieldAccessInputDto
{
    public function __construct(
        public UserId $userId,
        public string $permissionName
    ) {
    }
}
