<?php

namespace Modules\Authentication\Infrastructure\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Authentication\Domain\Events\UserRegistered;

class SendUserRegistered
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }


    public function handle(UserRegistered $event): void
    {
        Log::info("UserModel Registered at {$event->occurredAt->format('Y-m-d H:i:s')} email: {$event->user->email}");
    }
}
