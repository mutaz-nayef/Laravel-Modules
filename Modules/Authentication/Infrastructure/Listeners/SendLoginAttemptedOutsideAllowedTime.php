<?php

namespace Modules\Authentication\Infrastructure\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Events\LoginAttemptedOutsideAllowedTime;
use Modules\Authentication\Infrastructure\Events\UserAttemptLoginOutsideAllowedTime;

class SendLoginAttemptedOutsideAllowedTime
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(LoginAttemptedOutsideAllowedTime $event): void
    {
        broadcast(new UserAttemptLoginOutsideAllowedTime());

        $admins = $this->userRepository->getAdmins();

        foreach ($admins as $admin) {
            Mail::raw("User: {$event->aggregateId()} tried to login outside allowed time",
                function ($message) use ($event, $admin) {
                    $message->to($admin->email()->value())
                        ->subject('My Subject');
                });
        }
    }
}
