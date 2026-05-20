<?php

namespace Modules\Shared\Infrastructure\Services;

use Illuminate\Contracts\Events\Dispatcher;
use Modules\Shared\Abstractions\EventDispatcherInterface;

class LaravelEventDispatcher implements EventDispatcherInterface
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function dispatchAll(array $events): void
    {
        if (empty($events)) {
            return;
        }
        foreach ($events as $event) {
            $this->dispatcher->dispatch($event);
        }
    }

    public function dispatch(object $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
