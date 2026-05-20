<?php

namespace Modules\Authentication\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Authentication\Application\Actions\SendEmailVerificationNotificationAction;
use Modules\Authentication\Application\DTOs\Auth\Input\SendEmailVerificationInputDto;
use Modules\Authentication\Presentation\Http\Requests\EmailVerificationRequest;
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
                'message' => __('email.verification.sent'),
            ];
        });
    }

    public function verify(EmailVerificationRequest $request)
    {
        return $this->handle(function () use ($request) {
            $request->fulfill();
            return [
                'message' => __('email.verified'),
            ];
        });
    }
}
