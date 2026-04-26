<?php

namespace Modules\Authentication\Domain\Contracts;

Interface PasswordVerifierInterface
{
    public function verify(string $plain, string $hashed):bool;
}
