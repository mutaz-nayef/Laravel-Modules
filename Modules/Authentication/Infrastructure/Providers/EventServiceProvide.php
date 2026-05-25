<?php

namespace Modules\Authentication\Infrastructure\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Modules\Authentication\Domain\Events\LoginAttemptedOutsideAllowedTime;
use Modules\Authentication\Domain\Events\PasswordResetRequested;
use Modules\Authentication\Domain\Events\PasswordResetSuccessfully;
use Modules\Authentication\Domain\Events\UserLoggedIn;
use Modules\Authentication\Domain\Events\UserRegistered;
use Modules\Authentication\Infrastructure\Listeners\SendLoginAttemptedOutsideAllowedTime;
use Modules\Authentication\Infrastructure\Listeners\SendPasswordResetRequested;
use Modules\Authentication\Infrastructure\Listeners\SendPasswordResetSuccessfully;
use Modules\Authentication\Infrastructure\Listeners\SendUserLoggedIn;
use Modules\Authentication\Infrastructure\Listeners\SendUserRegistered;


class EventServiceProvide extends BaseEventServiceProvider

{
    protected $listen = [
        UserRegistered::class => [
            SendUserRegistered::class,
        ],
        UserLoggedIn::class => [
            SendUserLoggedIn::class,
        ],
        LoginAttemptedOutsideAllowedTime::class => [
            SendLoginAttemptedOutsideAllowedTime::class,
        ],
        PasswordResetRequested::class => [
            SendPasswordResetRequested::class,
        ],
        PasswordResetSuccessfully::class => [
            SendPasswordResetSuccessfully::class
        ]

    ];
}
