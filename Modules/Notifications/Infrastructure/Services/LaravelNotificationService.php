<?php

namespace Modules\Notifications\Infrastructure\Services;

use Illuminate\Support\Facades\Notification;
use Modules\Notifications\Application\DTO\NotificationDto;
use Modules\Notifications\Domain\Contracts\NotificationServiceInterface;
use Modules\Notifications\Infrastructure\Notifications\BaseNotification;
use Modules\Shared\Domain\Events\BaseDomainEvent;
use Modules\Shared\Domain\ValueObjects\UserId;

class LaravelNotificationService implements NotificationServiceInterface
{
    public function __construct()
    {
    }

    public function notify(UserId $userId, BaseDomainEvent $event): void
    {
        $user = $this->userRepository->findById($userId);

        $user->notify(new BaseNotification($event));
    }

    public function notifyAll(array $users, ?array $data): void
    {
        Notification::send($users, new BaseNotification(new NotificationDto(
            id: $data->id(),
            userId: $data->userId(),
            notificationTypeId: $data->notificationTypeId(),
            data: $data->data(),
        )));
    }
}
