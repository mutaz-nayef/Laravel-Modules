<?php

namespace Modules\Authentication\Infrastructure\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Authentication\Domain\Events\UserRegistered;
use Modules\Notifications\Domain\Contracts\NotifyUserInterface;

class SendUserRegistered
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }


    public function handle(UserRegistered $event): void
    {
        Log::info("User: {$event->aggregateId()} Registered at:{$event->occurredAt()->format('Y-m-d H:i:s')}");
    }
}
