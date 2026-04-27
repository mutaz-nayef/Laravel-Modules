<?php

namespace Modules\Authentication\Infrastructure\Services\Security;

use Modules\Authentication\Domain\Contracts\PasswordHasherInterface;

class PasswordHasherStrategy
{
    public function __construct(
        protected PasswordHasherInterface $hasher,
    )
    {
    }
        public function setHasherStrategy(PasswordHasherInterface $hasher):void
        {
            $this->hasher = $hasher;
        }

        public function make(string $password):string
        {
            return $this->hasher->make($password);
        }

        public function check(string $plain, string $hashed): bool
        {
            return $this->hasher->check($plain, $hashed);
        }
}
