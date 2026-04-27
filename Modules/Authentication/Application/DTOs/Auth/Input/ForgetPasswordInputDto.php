<?php

namespace Modules\Authentication\Application\DTOs\Auth\Input;

use Modules\Authentication\Domain\ValueObjects\Email;

final readonly class ForgetPasswordInputDto
{
    public function __construct(
        public Email $email,
    ) {
    }
}
