<?php

namespace Modules\Authentication\Application\DTO\Auth\Input;

use Modules\Shared\Domain\ValueObjects\UserId;

final readonly class SendEmailVerificationInputDto
{
    public function __construct(
        public UserId $userId,
    ) {
    }
}
