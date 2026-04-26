<?php

namespace Modules\Authentication\Domain\ValueObjects;

final readonly class HashedPassword
{
    public function __construct(private string $hash) {}

    public function verify(string $plain): bool {
        return password_verify($plain, $this->hash);
    }

    public function hash(): string {
        return $this->hash;
    }

    public static function fromPlain(string $plain): self {
        return new self (password_hash($plain, PASSWORD_DEFAULT));
    }
}
