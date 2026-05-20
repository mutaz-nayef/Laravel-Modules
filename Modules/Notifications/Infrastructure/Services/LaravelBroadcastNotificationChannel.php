<?php

namespace Modules\Notifications\Infrastructure\Services;

use Modules\Notifications\Domain\Contracts\NotificationChannelInterface;
use Modules\Notifications\Domain\Contracts\NotificationPayload;

class LaravelBroadcastNotificationChannel implements NotificationChannelInterface
{

    public function supports(string $channel): bool
    {
        return $channel === 'broadcast';
    }

    public function send(array $recipients, NotificationPayload $payload): void
    {
        broadcast($payload);
    }
}
