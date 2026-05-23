<?php

namespace Modules\UserManagement\Domain\ValueObjects;


use InvalidArgumentException;

final class Avatar
{
    public function __construct(private readonly string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Please enter a valid url address.', 422);
        }
    }
    
    public function value(): string
    {
        return $this->value;
    }

}
