<?php

namespace Modules\Authentication\Domain\Contracts;

use Modules\Authentication\Domain\Entities\User;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Notifications\Domain\ValueObjects\NotificationTypeId;
use Modules\Shared\Domain\ValueObjects\UserId;

interface UserRepositoryInterface
{
    public function findById(UserId $id): ?User;

    public function findByEmail(Email $email): ?User;

    public function save(User $user): ?User;

    public function saveRoles(User $user): ?User;

    public function savePermissions(User $user): ?User;

    /**
     * @return User[]|null
     */
    public function getAuthUsers(): ?array;

    /**
     * @return User[]|null
     */
    public function findByRole(string|RoleId $role): ?array;

    public function getUserEnabledChannels(
        UserId $userId,
        NotificationTypeId $notificationTypeId
    ): ?array;

    public function getAdmins(): ?array;

}
