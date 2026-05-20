<?php

namespace Modules\Notifications\Application\DTO\Input;

use Modules\Shared\Domain\ValueObjects\UserId;

class GetNotificationInputDto
{
    public function __construct(
        public UserId $userId,
    ) {
    }
}

