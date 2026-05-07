<?php

namespace Modules\Authorization\Domain\Services;

use Modules\Authorization\Domain\Contracts\FieldGuardServiceInterface;
use Modules\Authorization\Domain\Exceptions\ForbiddenFieldException;
use Modules\Authorization\Domain\ValueObjects\FieldPermissions;

class FieldGuardService implements FieldGuardServiceInterface
{

    public function guard(array $data, FieldPermissions $fieldPermission): void
    {
        $forbidden = $fieldPermission->findForbiddenWriteFields($data);
        if (!empty($forbidden)) {
            throw new ForbiddenFieldException($forbidden);
        }
    }
}
