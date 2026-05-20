<?php

namespace Modules\Notifications\Domain\Entities;

use Modules\Notifications\Domain\ValueObjects\NotificationId;
use Modules\Shared\Domain\ValueObjects\UserId;

final class Notification
{
    public function __construct(
        private readonly ?NotificationId $id,
//        private readonly NotificationTypeId $notificationTypeId,
        private readonly UserId $userId,
        private readonly string $data,
        private ?\DateTimeImmutable $readAt,
    ) {
    }

    public function markAsRead(): void
    {
        $this->readAt = new \DateTimeImmutable();
    }

//    public function notificationTypeId(): NotificationTypeId
//    {
//        return $this->notificationTypeId;
//    }

    public function data(): string
    {
        return $this->data;
    }

    public function readAt(): ?string
    {
        return $this->readAt;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function id(): NotificationId
    {
        return $this->id;
    }
}
