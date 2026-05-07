<?php

namespace Modules\Authorization\Domain\Services;

use Modules\Authorization\Domain\Contracts\FieldFilterServiceInterface;
use Modules\Authorization\Domain\ValueObjects\FieldPermissions;

class FieldFilterService implements FieldFilterServiceInterface
{

    public function filter(array $data, FieldPermissions $fieldPermission): array
    {
        return $fieldPermission->filterReadable($data);
    }
}
