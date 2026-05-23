<?php

namespace Modules\UserManagement\Domain\ValueObjects;


use InvalidArgumentException;

final class Bio
{
    public function __construct(private readonly string $text)
    {
        if (strlen($text) > 500) {
            throw new InvalidArgumentException("Bio too long");
        }
    }

    public function value(): string
    {
        return $this->text;
    }
}
