<?php

namespace Modules\Notifications\Domain\ValueObjects;

use InvalidArgumentException;

final class NotificationTypeId
{
    public function __construct(private readonly int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('Notification Type ID must be positive integer.', 401);
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
