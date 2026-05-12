<?php

namespace Modules\Authorization\Infrastructure\Listeners;

use Modules\Authentication\Domain\Events\UserRegistered;

class AssignNewUserRoleListener
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
    }
}
