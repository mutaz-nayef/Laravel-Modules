<?php

namespace Modules\Authentication\Infrastructure\Services\Security;

use Modules\Authentication\Domain\Contracts\EmailVerificationInterface;
use Modules\Authentication\Domain\Exceptions\EmailAlreadyVerifiedException;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Modules\Shared\Domain\ValueObjects\UserId;

class LaravelEmailVerification implements EmailVerificationInterface
{

    /**
     * @throws EmailAlreadyVerifiedException
     */
    public function sendEmailVerification(UserId $userId): void
    {
        $user = UserModel::findOrFail($userId->value());

        if ($user->hasVerifiedEmail()) {
            throw new EmailAlreadyVerifiedException('Email already verified.', 409);
        }
        $user->sendEmailVerificationNotification();
    }

    public function verify(UserId $userId): void
    {
    }

}
