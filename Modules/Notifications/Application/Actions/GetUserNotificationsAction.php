<?php

namespace Modules\Notifications\Application\Actions;

use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\UnAuthenticatedException;
use Modules\Notifications\Application\DTO\Input\GetNotificationInputDto;
use Modules\Notifications\Application\DTO\Output\NotificationOutputDto;
use Modules\Notifications\Domain\Contracts\NotificationRepositoryInterface;

class GetUserNotificationsAction
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {

    }

    /**
     * @throws UnAuthenticatedException
     */
    public function execute(GetNotificationInputDto $input): ?array
    {
        $user = $this->userRepository->findById($input->userId);
        if (!$user) {
            throw new UnAuthenticatedException('Unauthenticated User', 401);
        }
        $notifications = $this->notificationRepository->findForUser($user->id());

        return array_map(
            fn($notification) => new NotificationOutputDto(
                id: $notification->id(),
                typeId: $notification->notificationTypeId(),
                userId: $notification->userId(),
                data: $notification->data(),
            ),
            $notifications
        );
    }
}
