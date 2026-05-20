<?php

namespace Modules\Notifications\Domain\Contracts;

use Modules\Notifications\Domain\Entities\NotificationChannel;
use Modules\Notifications\Domain\ValueObjects\NotificationChannelId;

interface NotificationChannelRepositoryInterface
{
    public function findById(NotificationChannelId $notificationChannelId): ?NotificationChannel;

    public function save(NotificationChannel $notificationChannel): ?NotificationChannel;

    public function delete(NotificationChannelId $id): void;
}

