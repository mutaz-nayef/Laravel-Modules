<?php

namespace Modules\UserManagement\Application\DTOs\Inputs;

use Modules\Shared\Domain\ValueObjects\UserId;

class GetProfileInputDto
{
    public function __construct(public UserId $userId)
    {
    }
}
