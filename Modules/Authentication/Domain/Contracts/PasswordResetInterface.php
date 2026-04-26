<?php

namespace Modules\Authentication\Domain\Contracts;

use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authentication\Domain\ValueObjects\IssuedToken;

interface PasswordResetInterface
{
    public function sendResetLink(Email $email): string;

    public function reset(IssuedToken $token, Email $email, HashedPassword $password): string;
}
