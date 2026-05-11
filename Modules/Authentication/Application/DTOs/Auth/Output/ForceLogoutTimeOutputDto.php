<?php

namespace Modules\Authentication\Application\DTOs\Auth\Output;

class ForceLogoutTimeOutputDto
{
    public function __construct(
        public ?array $users
    ) {
    }
}
