<?php

namespace Modules\Notifications\Domain\Entities;

use Modules\Notifications\Domain\ValueObjects\NotificationTypeId;

final class NotificationType
{
    public function __construct(
        private readonly NotificationTypeId $id,
        private readonly string $name,
        private readonly string $group,
    ) {
    }

    public function id(): NotificationTypeId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function group(): string
    {
        return $this->group;
    }
}
