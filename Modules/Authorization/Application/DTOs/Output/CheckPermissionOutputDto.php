<?php

namespace Modules\Authorization\Application\DTOs\Output;

class CheckPermissionOutputDto
{
    public function __construct(
        public bool $allowed,
        public string $reason = '',   // useful for debugging / audit logs
    )
    {
    }
}
