<?php

namespace Modules\Shared\Abstractions;

interface EventDispatcherInterface
{
    public function dispatch(object $event): void;
}
