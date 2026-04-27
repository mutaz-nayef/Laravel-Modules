<?php

namespace Modules\Authentication\Infrastructure\Services\Security;

use Illuminate\Support\Facades\Hash;
use Modules\Authentication\Domain\Contracts\PasswordHasherInterface;

class LaravelPasswordHasher implements PasswordHasherInterface
{
    public function make(string $password):string
    {
        return Hash::make($password);
    }

    public function check(string $plain, string $password):bool
    {
        return Hash::check($plain, $password);
    }
}
