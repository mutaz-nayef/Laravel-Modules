<?php

namespace Modules\Authentication\Presentation\Http\Controllers;

use Modules\Authentication\Application\Actions\ForgetPasswordAction;
use Modules\Authentication\Application\Actions\PasswordResetAction;
use Modules\Authentication\Application\DTOs\Auth\Input\ForgetPasswordInputDto;
use Modules\Authentication\Application\DTOs\Auth\Input\PasswordResetInputDto;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Presentation\Http\Requests\ForgetPasswordRequest;
use Modules\Authentication\Presentation\Http\Requests\ResetPasswordRequest;
use Modules\Shared\Presentation\Http\Controllers\BaseController;

class PasswordResetController extends BaseController
{
    public function __construct(
        private readonly ForgetPasswordAction $forgetPasswordAction,
        private readonly PasswordResetAction $passwordResetAction,
    ) {
    }

    public function forget(ForgetPasswordRequest $request)
    {
        return $this->handle(function () use ($request) {

            $status = $this->forgetPasswordAction->execute(
                new ForgetPasswordInputDto(new Email($request->input('email')))
            );
            return [
                'message' => __($status),
            ];
        });
    }

    public function reset(ResetPasswordRequest $request)
    {

        return $this->handle(function () use ($request) {

            $status = $this->passwordResetAction->execute(
                new PasswordResetInputDto(
                    email: $request->input('email'),
                    password: $request->input('password'),
                    token: $request->input('token')
                )
            );

            return [
                'message' => __($status),
            ];
        });

    }
}
