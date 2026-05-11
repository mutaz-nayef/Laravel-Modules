<?php

namespace Modules\Authentication\Application\DTOs\Auth\Input;

class PasswordResetInputDto
{
    public function __construct(
        public string $email,
        public string $password,
        public string $token,
    ) {
    }
}
