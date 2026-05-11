<?php


namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Application\DTOs\Auth\Input\ForgetPasswordInputDto;
use Modules\Authentication\Domain\Contracts\PasswordResetInterface;

class ForgetPasswordAction
{
    public function __construct(
        protected PasswordResetInterface $passwordReset,
    ) {
    }


    public function execute(ForgetPasswordInputDto $input): string
    {
        $status = $this->passwordReset->sendResetLink($input->email);

        //fix
//        if ($status) {
//            event(new PasswordResetRequested($input->email));
//        }
        return $status;
    }
}
