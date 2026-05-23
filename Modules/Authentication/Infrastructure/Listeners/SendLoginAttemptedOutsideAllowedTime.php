<?php

namespace Modules\Authentication\Infrastructure\Listeners;

use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Events\LoginAttemptedOutsideAllowedTime;
use Modules\Authentication\Infrastructure\UserAttemptLoginOutsideAllowedTime;
use Modules\Notifications\Application\Actions\StoreNotificationsAction;
use Modules\Notifications\Application\DTO\Input\StoreNotificationInputDto;
use Illuminate\Support\Facades\Mail;

class SendLoginAttemptedOutsideAllowedTime
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly StoreNotificationsAction $storeNotificationsAction,
        private readonly UserRepositoryInterface $userRepository,
//        private readonly Notification
    )
    {
    }

    /**
     * Handle the event.
     */
    public function handle(LoginAttemptedOutsideAllowedTime $event): void
    {
        broadcast(new UserAttemptLoginOutsideAllowedTime());

        $admins = $this->userRepository->getAdmins();


//        Notification::send(UserModel::find(1), new BaseNotification(
//            message: 'user try to login outside allowed time',
//            data: [$event->aggregateId(), $event->occurredAt()]
//        ));
        foreach ($admins as $admin) {
            Mail::raw("User: {$event->aggregateId()} tried to login outside allowed time",
                function ($message) use ($event, $admin) {
                    $message->to($admin->email()->value())
                        ->subject('My Subject');
                });
        }
        $this->storeNotificationsAction->execute(
            new StoreNotificationInputDto(
                recipientIds: array_map(fn($admin) => $admin->id(), $admins),
                data: "User: {$event->aggregateId()} tried to login outside allowed time",
            )
        );
    }
}
