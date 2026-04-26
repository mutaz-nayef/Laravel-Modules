<?php

namespace Modules\Authentication\Application\DTO\Auth\Output;


final readonly class AuthOutputDto
{
    public function __construct(
        public int $userId,
        public string $name,
        public string $email,
        public string $token,
        public string $tokenType,
        public string $roleName,
        public array $permissions,
        public ?array $timeNow = null,
    ) {

    }

}
