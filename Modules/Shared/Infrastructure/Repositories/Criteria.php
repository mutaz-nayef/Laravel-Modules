<?php

namespace Modules\Shared\Infrastructure\Repositories;

class Criteria
{

    public static function matches(string $fieldName, string $pattern): Criteria
    {
        return new MatchCriteria($fieldName, $pattern);
    }

}
