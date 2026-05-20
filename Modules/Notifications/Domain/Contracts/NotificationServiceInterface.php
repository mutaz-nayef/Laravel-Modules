<?php

namespace Modules\Notifications\Domain\Contracts;

use Modules\Authentication\Domain\Entities\User;
use Modules\Shared\Domain\Events\BaseDomainEvent;
use Modules\Shared\Domain\ValueObjects\UserId;

interface NotificationServiceInterface
{
    public function notify(UserId $userId, BaseDomainEvent $event): void;

    /**
     * @param  User[]  $users
     */
    public function notifyAll(array $users, ?array $data): void;
}
