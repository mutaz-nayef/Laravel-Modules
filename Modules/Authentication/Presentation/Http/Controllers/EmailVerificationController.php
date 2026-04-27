<?php

namespace Modules\Authentication\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Authentication\Application\Actions\SendEmailVerificationNotificationAction;
use Modules\Authentication\Application\DTOs\Auth\Input\SendEmailVerificationInputDto;
use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\Shared\Presentation\Http\Controllers\BaseController;

class EmailVerificationController extends BaseController
{

    public function __construct(
        private readonly SendEmailVerificationNotificationAction $sendEmailVerificationAction
    ) {
    }

    public function send(Request $request)
    {
        return $this->handle(function () use ($request) {

            $this->sendEmailVerificationAction->execute(
                new SendEmailVerificationInputDto(
                    userId: new UserId($request->user()->id)
                )
            );
            return [
                'message' => 'Verification link sent!',
            ];
        });
    }

    public function verify(\Modules\Authentication\Presentation\Http\Requests\EmailVerificationRequest $request)
    {
        return $this->handle(function () use ($request) {
            $request->fulfill();
            return [
                'message' => 'Email has been verified',
            ];
        });
    }
}
