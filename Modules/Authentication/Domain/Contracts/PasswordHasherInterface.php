<?php

namespace Modules\Authentication\Domain\Contracts;

Interface PasswordHasherInterface
{
    public function make(string $password):string;

    public function check(string $plain, string $password):bool;
}
