<?php

namespace Modules\UserManagement\Domain\Contracts;

use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\UserManagement\Domain\Entities\Profile;

interface ProfileRepositoryInterface
{
    public function getByUserId(UserId $userId): Profile;

    public function save(Profile $profile): void;
}
