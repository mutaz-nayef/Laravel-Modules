<?php

namespace Modules\Authorization\Domain\Contracts;

use Modules\Authorization\Domain\Exceptions\ForbiddenFieldException;
use Modules\Authorization\Domain\ValueObjects\FieldPermissions;

interface FieldGuardServiceInterface
{
    /**
     * Throw ForbiddenFieldException if $input contains fields the user cannot write.
     *
     * @param  array<string,mixed>  $data
     * @throws ForbiddenFieldException
     */
    public function guard(array $data, FieldPermissions $fieldPermission): void;
}
