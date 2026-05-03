<?php

namespace Modules\Authorization\Infrastructure\Listeners;

use Modules\Authentication\Domain\Events\UserRegistered;
use Modules\Authentication\Infrastructure\Enums\Roles;
use Modules\Authorization\Domain\Contracts\UserRoleInterface;

class AssignNewUserRoleListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected UserRoleInterface $userRole,
    ) {
        //
    }


    public function handle(UserRegistered $event): void
    {
        $this->userRole->assignRole($event->user->id, Roles::default());
    }
}
