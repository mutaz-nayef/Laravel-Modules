<?php

namespace Modules\Authorization\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Authorization\Domain\Events\RolePermissionUpdated;
use Modules\Authorization\Infrastructure\Listeners\SendRolePermissionUpdatedListener;


class EventServiceProvide extends BaseEventServiceProvider

{
    protected $listen = [
        RolePermissionUpdated::class => [
            SendRolePermissionUpdatedListener::class,
        ],
    ];
}
