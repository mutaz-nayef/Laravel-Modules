<?php

namespace Modules\UserManagement\Domain\ValueObjects;


final class PhoneNumber
{
    public function __construct(private readonly string $value)
    {
        if (!self::isValid($value)) {
            throw new \InvalidArgumentException(
                "Invalid phone number. Expected format: 059xxxxxxx"
            );
        }

    }

    public static function isValid(string $value): bool
    {
        return (bool) preg_match('/^(056|059)\d{7}$/', $value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
