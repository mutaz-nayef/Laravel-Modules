<?php

namespace Modules\Notifications\Infrastructure\Repositories;

use Modules\Notifications\Domain\Contracts\NotificationChannelRepositoryInterface;
use Modules\Notifications\Domain\Entities\NotificationChannel;
use Modules\Notifications\Domain\ValueObjects\NotificationChannelId;

class EloquentNotificationChannelRepository implements NotificationChannelRepositoryInterface
{
    public function findById(NotificationChannelId $notificationChannelId): ?NotificationChannel
    {

    }

    public function save(NotificationChannel $notificationChannel): ?NotificationChannel
    {

    }

    public function delete(NotificationChannelId $id): void
    {

    }
}

