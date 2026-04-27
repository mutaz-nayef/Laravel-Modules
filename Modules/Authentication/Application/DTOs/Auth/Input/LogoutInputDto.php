<?php

namespace Modules\Authentication\Application\DTOs\Auth\Input;

use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class LogoutInputDto
{
    public function __construct(
        public UserId $userId,
    ) {
    }
}
