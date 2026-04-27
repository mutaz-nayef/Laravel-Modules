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
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LoginAttemptedOutsideAllowedTime $event): void
    {
        Log::info("UserModel attempted loginInputDto outside allowed time: {$event->email} at {$event->occurredAt->format('Y-m-d H:i:s')}");
    }
}
