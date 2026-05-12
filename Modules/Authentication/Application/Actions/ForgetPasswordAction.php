<?php


namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Application\DTOs\Auth\Input\ForgetPasswordInputDto;
use Modules\Authentication\Domain\Contracts\PasswordResetInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Events\PasswordResetSuccessfully;
use Modules\Authentication\Infrastructure\Events\EventDispatcher;

class ForgetPasswordAction
{
    public function __construct(
        protected PasswordResetInterface $passwordReset,
        private readonly EventDispatcher $eventDispatcher,
        private readonly UserRepositoryInterface $userRepository,

    ) {
    }


    public function execute(ForgetPasswordInputDto $input): string
    {
        $status = $this->passwordReset->sendResetLink($input->email);

        $user = $this->userRepository->findByEmail($input->email);

        if ($status) {
            $this->eventDispatcher->dispatch(new PasswordResetSuccessfully($user->id()));
        }
        return $status;
    }
}
