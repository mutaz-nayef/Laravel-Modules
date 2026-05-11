<?php

namespace Modules\Shared\Domain\Events;


trait RecordsEvents
{
    private array $domainEvents = [];

    public function pullDomainEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];
        return $events;
    }

    protected function recordThat(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }
}
