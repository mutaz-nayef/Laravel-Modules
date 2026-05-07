<?php

namespace Modules\Authorization\Domain\Contracts;

use Modules\Authorization\Domain\ValueObjects\FieldPermissions;

interface FieldFilterServiceInterface
{
    /**
     * Remove fields that user cannot read from data array.
     * Used before returning resource data to the client.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function filter(array $data, FieldPermissions $fieldPermission): array;
}
