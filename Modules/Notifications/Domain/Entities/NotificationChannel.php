<?php

namespace Modules\Notifications\Domain\Entities;

use Modules\Notifications\Domain\ValueObjects\NotificationChannelId;

final class NotificationChannel
{
    public function __construct(
        private readonly NotificationChannelId $id,
        private readonly string $name, // email
        private readonly string $displayName, // Email
    )
    {
    }

    public function id(): NotificationChannelId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function displayName(): string
    {
        return $this->displayName;
    }
}
