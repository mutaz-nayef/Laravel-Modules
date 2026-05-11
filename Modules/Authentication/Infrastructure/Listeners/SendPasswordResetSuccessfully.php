<?php

namespace Modules\Authentication\Infrastructure\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Authentication\Domain\Events\PasswordResetSuccessfully;

class SendPasswordResetSuccessfully
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
    public function handle(PasswordResetSuccessfully $event): void
    {
        Log::info("Password Reset Request: {$event->aggregateId()} at {$event->occurredAt()->format('Y-m-d H:i:s')}");
    }
}
