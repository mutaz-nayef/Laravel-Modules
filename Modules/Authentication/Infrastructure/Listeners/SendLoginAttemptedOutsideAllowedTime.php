<?php

namespace Modules\Authentication\Infrastructure\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Authentication\Domain\Events\LoginAttemptedOutsideAllowedTime;

class SendLoginAttemptedOutsideAllowedTime
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(LoginAttemptedOutsideAllowedTime $event): void
    {

        Log::info(
            "Login blocked for user {$event->aggregateId()} at {$event->occurredAt()->format('Y-m-d H:i:s')}"
        );
    }
}
