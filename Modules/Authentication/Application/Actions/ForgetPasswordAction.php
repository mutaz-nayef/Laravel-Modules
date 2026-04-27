<?php


namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Application\DTOs\Auth\Input\ForgetPasswordInputDto;
use Modules\Authentication\Domain\Contracts\PasswordResetInterface;
use Modules\Authentication\Domain\Events\PasswordResetRequested;

class ForgetPasswordAction
{
    public function __construct(
        protected PasswordResetInterface $passwordReset,
    ) {
    }


    public function execute(ForgetPasswordInputDto $input): string
    {
        $status = $this->passwordReset->sendResetLink($input->email);

        if ($status) {
            event(new PasswordResetRequested($input->email));
        }
        return $status;
    }
}
