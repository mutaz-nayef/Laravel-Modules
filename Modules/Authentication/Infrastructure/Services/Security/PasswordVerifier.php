<?php
namespace Modules\Authentication\Infrastructure\Services\Security;
use Modules\Authentication\Domain\Contracts\PasswordVerifierInterface;

class PasswordVerifier implements PasswordVerifierInterface
{
    public function __construct(protected PasswordHasherStrategy $passwordHasherStrategy)
    {}

    public function verify(string $plain, string $hashed):bool
    {
        if(str_starts_with($hashed, '$2y$')) {
            $this->passwordHasherStrategy->setHasherStrategy(new LaravelPasswordHasher());
        }
        if(strlen($hashed) === 32) {
            $this->passwordHasherStrategy->setHasherStrategy(new Md5PasswordHasher());
        }
        return $this->passwordHasherStrategy->check($plain, $hashed);
    }
}
