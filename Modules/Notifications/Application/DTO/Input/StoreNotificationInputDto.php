<?php

namespace Modules\Notifications\Application\DTO\Input;

use Modules\Shared\Domain\ValueObjects\UserId;

class StoreNotificationInputDto
{

    /**
     * @param  UserId[]  $recipientIds
     */
    public function __construct(
        public array $recipientIds,
        public string $data
    ) {
    }
}

