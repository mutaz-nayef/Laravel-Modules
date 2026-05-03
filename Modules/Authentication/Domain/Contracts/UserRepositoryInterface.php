<?php

namespace Modules\Authentication\Domain\Contracts;

use Modules\Authentication\Domain\Entities\User;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Shared\Domain\ValueObjects\UserId;

interface UserRepositoryInterface
{
    public function findById(UserId $id): ?User;

    public function findByEmail(Email $email): ?User;

    public function save(User $user): ?User;

    public function saveRoles(User $user): ?User;

    public function savePermissions(User $user): ?User;

}
