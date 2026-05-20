<?php

namespace Modules\Notifications\Application\DTO\Output;

use Modules\Notifications\Domain\ValueObjects\NotificationId;
use Modules\Notifications\Domain\ValueObjects\NotificationTypeId;
use Modules\Shared\Domain\ValueObjects\UserId;

class NotificationOutputDto
{
    public function __construct(
        public NotificationId $id,
        public NotificationTypeId $typeId,
        public UserId $userId,
        public string $data,
        public ?\DateTimeImmutable $readAt = null,
    ) {
    }
}

