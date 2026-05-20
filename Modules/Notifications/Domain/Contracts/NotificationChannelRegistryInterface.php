<?php

namespace Modules\Notifications\Domain\Contracts;

interface NotificationChannelRegistryInterface

{
    public function register(NotificationChannelInterface $channel): void;

    /** @return NotificationChannelInterface[] */
    public function all(): array;

    public function get(string $channelName): NotificationChannelInterface;

}
