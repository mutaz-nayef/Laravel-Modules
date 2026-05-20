<?php

namespace Modules\Notifications\Domain\ValueObjects;

use InvalidArgumentException;

final class NotifiableId
{
    public function __construct(
        private readonly int $id,
        private readonly string $type, // App\Models\User
    )
    {
        if (empty($type)) {
            throw new InvalidArgumentException("Notifiable type cannot be empty");
        }
    }

    public function id(): int
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function equals(self $other): bool
    {
        return $this->id === $other->id
            && $this->type === $other->type;
    }
}
