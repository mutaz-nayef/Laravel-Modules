<?php

namespace Modules\Authentication\Infrastructure\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Authentication\Domain\Events\UserLoggedIn;

class SendUserLoggedIn
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        Log::info("UserModel logged in: {$event->email->value()} at {$event->occurredAt->format('Y-m-d H:i:s')}");
    }
}
