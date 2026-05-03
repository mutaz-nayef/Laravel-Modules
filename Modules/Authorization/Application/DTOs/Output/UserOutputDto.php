<?php

namespace Modules\Authorization\Application\DTOs\Output;

use Modules\Shared\Domain\ValueObjects\UserId;

class UserOutputDto
{
    public function __construct(
        public UserId $id,
        public string $name,
        public string $email,
        public ?array $roles,
        public array $permissions,
    ) {
    }
}
