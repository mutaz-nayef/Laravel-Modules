<?php

namespace Modules\Authentication\Domain\Events;


use Modules\Authentication\Domain\ValueObjects\Email;

abstract class BaseEvent
{
    public function __construct(
        public Email $email,
        public \DateTimeImmutable $occurredAt = new \DateTimeImmutable()
    )
    {}
}
