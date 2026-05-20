<?php

namespace Modules\Notifications\Domain\Contracts;

use Modules\Notifications\Domain\Entities\NotificationType;
use Modules\Notifications\Domain\ValueObjects\NotificationTypeId;

interface NotificationTypeRepositoryInterface
{
    public function findById(NotificationTypeId $notificationTypeId): ?NotificationType;

    public function save(NotificationType $notificationChannel): ?NotificationType;

    public function delete(NotificationTypeId $id): void;
}

