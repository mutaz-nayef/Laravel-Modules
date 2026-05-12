<?php

namespace Modules\Shared\Infrastructure\Events;

final class EventDispatcher
{
    public function dispatchAll(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    public function dispatch(object $event): void
    {
        event($event);
    }
}
