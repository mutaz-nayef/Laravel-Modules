<?php

namespace Modules\Authorization\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Authentication\Domain\Events\UserRegistered;
use Modules\Authorization\Domain\Events\RolePermissionUpdated;
use Modules\Authorization\Infrastructure\Listeners\AssignNewUserRoleListener;
use Modules\Authorization\Infrastructure\Listeners\SendRolePermissionUpdatedListener;


class EventServiceProvide extends BaseEventServiceProvider

{
    protected $listen = [
        UserRegistered::class => [
            AssignNewUserRoleListener::class, // ← Authorization listener registered inside Auth!
        ],
        RolePermissionUpdated::class => [
            SendRolePermissionUpdatedListener::class,
        ],
    ];
}
