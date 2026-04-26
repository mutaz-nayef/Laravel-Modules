<?php

namespace Modules\Authentication\Infrastructure\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Authentication\Domain\Events\PasswordResetRequested;
use Modules\Authentication\Domain\Events\UserLoggedIn;

class SendPasswordResetRequested
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
    public function handle(PasswordResetRequested $event): void
    {
        Log::info("Password Reset Request: {$event->email} at {$event->occurredAt->format('Y-m-d H:i:s')}");
    }
}
