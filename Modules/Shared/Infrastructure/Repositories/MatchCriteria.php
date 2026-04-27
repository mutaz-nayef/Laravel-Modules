<?php

namespace Modules\Shared\Infrastructure\Repositories;

class MatchCriteria extends Criteria
{
    public function __construct(
        public string $fieldName,
        public string $pattern
    ) {
    }
}
