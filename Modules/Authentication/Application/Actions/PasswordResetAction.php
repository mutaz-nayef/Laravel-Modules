<?php


namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Application\DTOs\Auth\Input\PasswordResetInputDto;
use Modules\Authentication\Domain\Contracts\PasswordResetInterface;
use Modules\Authentication\Domain\Events\PasswordResetSuccessfully;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authentication\Domain\ValueObjects\IssuedToken;

class PasswordResetAction
{
    public function __construct(
        private readonly PasswordResetInterface $passwordReset,
    ) {
    }


    public function execute(PasswordResetInputDto $input): string
    {

        $email = new Email($input->email);
        $status = $this->passwordReset->reset(
            token: new IssuedToken($input->token),
            email: $email,
            password: new HashedPassword($input->password),
        );

        if ($status) {
            event(new PasswordResetSuccessfully($email));
        }
        return $status;
    }
}
