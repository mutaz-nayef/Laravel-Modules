<?php

namespace Modules\Authorization\Application\DTOs\Output;

class FieldAccessOutputDto
{

    /**
     * @param  string[]|null  $readableFields  null = all fields allowed
     * @param  string[]|null  $writableFields  null = all fields allowed
     */
    public function __construct(
        public ?array $readableFields,
        public ?array $writableFields,
    ) {
    }
}
