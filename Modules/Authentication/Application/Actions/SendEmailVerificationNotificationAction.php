<?php


namespace Modules\Authentication\Application\Actions;


use Modules\Authentication\Application\DTOs\Auth\Input\SendEmailVerificationInputDto;
use Modules\Authentication\Domain\Contracts\EmailVerificationInterface;

class SendEmailVerificationNotificationAction
{
    public function __construct(
        protected EmailVerificationInterface $emailVerification,
    ) {
    }

    public function execute(SendEmailVerificationInputDto $input): void
    {
        $this->emailVerification->sendEmailVerification($input->userId);
    }
}
