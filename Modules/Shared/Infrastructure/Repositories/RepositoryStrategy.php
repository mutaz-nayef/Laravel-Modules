<?php

namespace Modules\Shared\Infrastructure\Repositories;

interface RepositoryStrategy
{
    public function matching(Criteria $criteria);

}
