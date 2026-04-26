<?php

namespace Modules\Shared\Domain;

abstract class AggregateRoot
{
    
    /**
     * Create aggregate from array (for reconstruction from DB)
     */
    abstract public static function fromArray(array $data): static;

    abstract public function toArray(): array;
}
