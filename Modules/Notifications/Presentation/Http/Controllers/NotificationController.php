<?php

namespace Modules\Notifications\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Notifications\Application\Actions\GetUserNotificationsAction;
use Modules\Notifications\Application\DTO\Input\GetNotificationInputDto;
use Modules\Notifications\Presentation\Http\Resources\NotificationResource;
use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\Shared\Presentation\Http\Controllers\BaseController;

class NotificationController extends BaseController
{
    public function __construct(
        private readonly GetUserNotificationsAction $getUserNotificationsAction,
    ) {
    }

    public function index(Request $request)
    {
        return $this->handle(function () use ($request) {

            $notifications = $this->getUserNotificationsAction->execute(
                new GetNotificationInputDto(
                    userId: new UserId($request->user()->id)
                )
            );
            return [
                'message' => 'Notifications returned successfully.',
                'data' => NotificationResource::collection($notifications),
            ];
        });
    }
}
