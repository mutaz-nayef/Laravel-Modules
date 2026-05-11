<?php

namespace Modules\Authentication\Application\DTOs\Auth\Input;

class RegisterInputDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {
    }
}
