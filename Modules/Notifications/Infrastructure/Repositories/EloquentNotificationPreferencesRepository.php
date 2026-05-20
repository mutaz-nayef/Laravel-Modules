<?php

namespace Modules\Notifications\Infrastructure\Repositories;

use Modules\Notifications\Domain\Contracts\NotificationPreferencesRepositoryInterface;
use Modules\Notifications\Domain\Entities\NotificationPreferences;
use Modules\Notifications\Domain\ValueObjects\NotificationPreferencesId;
use Modules\Shared\Domain\ValueObjects\UserId;

class EloquentNotificationPreferencesRepository implements NotificationPreferencesRepositoryInterface
{

    public function findForUser(UserId $userId): ?array
    {
        // TODO: Implement findForUser() method.
    }

    public function findById(NotificationPreferencesId $notificationPreferencesId): ?NotificationPreferences
    {
        // TODO: Implement findById() method.
    }

    public function save(NotificationPreferences $notificationPreferences): ?NotificationPreferences
    {
        // TODO: Implement save() method.
    }

    public function delete(NotificationPreferencesId $id): void
    {
        // TODO: Implement delete() method.
    }
}

