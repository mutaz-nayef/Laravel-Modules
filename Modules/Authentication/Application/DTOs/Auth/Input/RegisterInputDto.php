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

    public static function fromArray(array $data): RegisterInputDto
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}
