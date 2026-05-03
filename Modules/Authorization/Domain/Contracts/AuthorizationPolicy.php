<?php

namespace Modules\Authorization\Domain\Contracts;

interface AuthorizationPolicy
{
    public function check($subject, $resource, $action, $conditions): bool;
}
