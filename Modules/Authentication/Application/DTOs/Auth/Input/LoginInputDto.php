<?php

namespace Modules\Authentication\Application\DTOs\Auth\Input;

final readonly class LoginInputDto
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }

    public static function fromArray(array $data): LoginInputDto
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
        );
    }
}
