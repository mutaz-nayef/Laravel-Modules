<?php

namespace Modules\Notifications\Application\DTO;

use Modules\Notifications\Domain\ValueObjects\NotificationId;
use Modules\Notifications\Domain\ValueObjects\NotificationTypeId;
use Modules\Shared\Domain\ValueObjects\UserId;

class NotificationDto
{
    public function __construct(
        public NotificationId $id,
        public UserId $userId,
        public NotificationTypeId $notificationTypeId,
        public string $data,
        public ?\DateTimeImmutable $readAt = null,
    ) {
    }

}
