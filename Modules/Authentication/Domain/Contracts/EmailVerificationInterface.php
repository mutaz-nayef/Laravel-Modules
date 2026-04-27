<?php

namespace Modules\Authentication\Domain\Contracts;

use Modules\Shared\Domain\ValueObjects\UserId;

interface EmailVerificationInterface

{
    public function sendEmailVerification(UserId $userId): void;

    public function verify(UserId $userId): void;
}
