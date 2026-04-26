<?php

namespace Modules\Shared\Domain\ValueObjects;

use InvalidArgumentException;

final class UserId
{
    public function __construct(private readonly int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('User ID must be positive integer.', 401);
        }
    }

    public static function temporary(): self
    {
        return new self(1);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }

    public function value(): int
    {
        return $this->value;
    }

}
