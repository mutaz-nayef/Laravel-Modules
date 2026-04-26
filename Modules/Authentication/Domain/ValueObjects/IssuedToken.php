<?php

namespace Modules\Authentication\Domain\ValueObjects;

final class IssuedToken
{
    public function __construct(private readonly string $plainText) {}


    public function plainText(): string
    {
        return $this->plainText;
    }
}
