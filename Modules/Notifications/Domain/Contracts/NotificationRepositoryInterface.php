<?php

namespace Modules\Notifications\Domain\Contracts;

use Modules\Notifications\Domain\Entities\Notification;
use Modules\Notifications\Domain\ValueObjects\NotificationId;
use Modules\Shared\Domain\ValueObjects\UserId;

interface NotificationRepositoryInterface
{
    public function findForUser(UserId $userId): ?array;

    public function findById(NotificationId $notificationId): ?Notification;

    public function save(Notification $notification): ?Notification;

    public function delete(NotificationId $id): void;

    public function bulkInsert(array $notifications): void;

}

