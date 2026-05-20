<?php

namespace Modules\Notifications\Application\Services;

use Modules\Notifications\Domain\Contracts\NotificationChannelRegistryInterface;

class NotificationDispatcher
{
    public function __construct(
        private readonly NotificationChannelRegistryInterface $registry,
    ) {
    }

    public function dispatch(array $recipients, NotificationPayload $payload): void
    {
        foreach ($this->registry->all() as $channel) {
            $channel->send($recipients, $payload);
        }
    }
}
