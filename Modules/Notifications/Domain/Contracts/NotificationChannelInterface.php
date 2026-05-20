<?php

namespace Modules\Notifications\Domain\Contracts;

interface NotificationChannelInterface

{
    public function supports(string $channel): bool;

    /**
     * @param  array  $recipients
     * @param  NotificationPayload  $payload
     */
    public function send(array $recipients, NotificationPayload $payload): void;

}
