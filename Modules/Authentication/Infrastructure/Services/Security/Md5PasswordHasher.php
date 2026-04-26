<?php

namespace Modules\Authentication\Infrastructure\Services\Security;

use Modules\Authentication\Domain\Contracts\PasswordHasherInterface;

class Md5PasswordHasher implements PasswordHasherInterface
{

    public function make(string $password): string
    {
        return md5($password);
    }

    public function check(string $plain, string $hashed): bool
    {
        return md5($plain) === $hashed;
    }
}
