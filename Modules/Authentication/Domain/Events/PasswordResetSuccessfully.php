<?php

namespace Modules\Authentication\Domain\Events;


use Modules\Shared\Domain\Events\BaseDomainEvent;
use Modules\Shared\Domain\ValueObjects\UserId;

class PasswordResetSuccessfully extends BaseDomainEvent
{

    public function __construct(
        public readonly UserId $userId,
    ) {
        parent::__construct();
    }

    public function aggregateId(): string
    {
        return (string) $this->userId->value();
    }
}
