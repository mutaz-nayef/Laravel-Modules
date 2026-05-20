<?php

namespace Modules\Notifications\Domain\Contracts;

use Modules\Notifications\Domain\Entities\NotificationPreferences;
use Modules\Notifications\Domain\ValueObjects\NotificationPreferencesId;
use Modules\Shared\Domain\ValueObjects\UserId;

interface NotificationPreferencesRepositoryInterface
{
    public function findForUser(UserId $userId): ?array;

    public function findById(NotificationPreferencesId $notificationPreferencesId): ?NotificationPreferences;

    public function save(NotificationPreferences $notificationPreferences): ?NotificationPreferences;

    public function delete(NotificationPreferencesId $id): void;

}

