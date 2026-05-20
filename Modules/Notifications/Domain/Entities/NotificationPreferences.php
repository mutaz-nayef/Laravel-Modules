<?php

namespace Modules\Notifications\Domain\Entities;

use Modules\Notifications\Domain\ValueObjects\EnablesChannels;
use Modules\Notifications\Domain\ValueObjects\NotificationPreferencesId;
use Modules\Notifications\Domain\ValueObjects\NotificationTypeId;
use Modules\Shared\Domain\ValueObjects\UserId;

final class NotificationPreferences
{
    public function __construct(
        private readonly NotificationPreferencesId $id,
        private readonly UserId $userId,
        private readonly NotificationTypeId $typeId,
        private readonly EnablesChannels $channels,
    ) {
    }

    public function id(): NotificationPreferencesId
    {
        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function typeId(): NotificationTypeId
    {
        return $this->typeId;
    }

    public function channels(): EnablesChannels
    {
        return $this->channels;
    }


}
