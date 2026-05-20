<?php

namespace Modules\Notifications\Application\Actions;

use Modules\Notifications\Application\DTO\Input\StoreNotificationInputDto;
use Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface;
use Modules\Notifications\Domain\Entities\Notification;

class StoreNotificationsAction
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
    ) {

    }

    public function execute(StoreNotificationInputDto $input): void
    {
        $notifications = array_map(fn($userId) => new Notification(
            id: null,
            userId: $userId,
            data: $input->data,
            readAt: null,
        ), $input->recipientIds);

        $this->notificationRepository->bulkInsert($notifications);
    }
}
