<?php

namespace Modules\Notifications\Infrastructure\Services;

use Modules\Notifications\Domain\Contracts\NotificationChannelInterface;
use Modules\Notifications\Domain\Contracts\NotificationChannelRegistryInterface;

class NotificationChannelRegistry implements NotificationChannelRegistryInterface
{

    /** @var NotificationChannelInterface[] */
    private array $channels = [];

    public function register(NotificationChannelInterface $channel): void
    {
        $this->channels[] = $channel;
    }

    public function all(): array
    {
        return $this->channels;
    }

    public function get(string $channelName): NotificationChannelInterface
    {
        foreach ($this->channels as $channel) {
            if ($channel->supports($channelName)) {
                return $channel;
            }
        }
        throw new \RuntimeException("No channel registered for: {$channelName}");

    }
}
