<?php

namespace Modules\Shared\Infrastructure\Repositories;

abstract class Repository
{

    public function __construct(protected RepositoryStrategy $strategy)
    {
    }

    public function matching(Criteria $criteria)
    {
        return $this->strategy->matching($criteria);
    }
}
