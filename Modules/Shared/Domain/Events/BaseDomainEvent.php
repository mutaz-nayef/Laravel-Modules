<?php

namespace Modules\Shared\Domain\Events;

use DateTimeImmutable;

interface DomainEvent
{
    public function occurredAt(): DateTimeImmutable;

    public function aggregateId(): string;
}

abstract class BaseDomainEvent implements DomainEvent
{

    private DateTimeImmutable $occurredAt;

    public function __construct()
    {
        $this->occurredAt = new DateTimeImmutable();
    }

    public function occurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
