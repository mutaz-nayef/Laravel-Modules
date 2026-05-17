<?php


namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Application\DTOs\Auth\Input\PasswordResetInputDto;
use Modules\Authentication\Domain\Contracts\PasswordResetInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Events\PasswordResetSuccessfully;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authentication\Domain\ValueObjects\IssuedToken;
use Modules\Shared\Abstractions\EventDispatcherInterface;

class PasswordResetAction
{
    public function __construct(
        private readonly PasswordResetInterface $passwordReset,
        private readonly UserRepositoryInterface $userRepository,
        private readonly EventDispatcherInterface $dispatcher,

    ) {
    }


    public function execute(PasswordResetInputDto $input): string
    {
        $email = new Email($input->email);
        $status = $this->passwordReset->reset(
            token: new IssuedToken($input->token),
            email: $email,
            password: HashedPassword::fromPlain($input->password),
        );

        $user = $this->userRepository->findByEmail($email);
        if ($status) {
            $this->dispatcher->dispatch(new PasswordResetSuccessfully($user->id()));
        }
        return $status;
    }
}
