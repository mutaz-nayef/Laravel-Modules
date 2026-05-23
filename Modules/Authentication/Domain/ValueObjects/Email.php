<?php

namespace Modules\Authentication\Domain\ValueObjects;


use InvalidArgumentException;

final class Email
{
    public function __construct(private readonly string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Please enter a valid email address.', 422);
        }
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value;
    }
}
