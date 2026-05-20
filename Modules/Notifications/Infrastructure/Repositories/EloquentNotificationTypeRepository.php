<?php

namespace Modules\Notifications\Infrastructure\Repositories;

use Modules\Notifications\Domain\Contracts\NotificationTypeRepositoryInterface;
use Modules\Notifications\Domain\Entities\NotificationType;
use Modules\Notifications\Domain\ValueObjects\NotificationTypeId;

class EloquentNotificationTypeRepository implements NotificationTypeRepositoryInterface
{
    public function findById(NotificationTypeId $notificationTypeId): ?NotificationType
    {
        // TODO: Implement findById() method.
    }

    public function save(NotificationType $notificationChannel): ?NotificationType
    {
        // TODO: Implement save() method.
    }

    public function delete(NotificationTypeId $id): void
    {
        // TODO: Implement delete() method.
    }
}

