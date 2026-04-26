<?php

namespace Modules\Authentication\Infrastructure\Services;

use DateTimeZone;

class GetTimeNowService
{
    public function __construct(
        protected \DateTimeImmutable $time
    ) {
    }

    /**
     * @throws \DateInvalidTimeZoneException
     */
    public function getTimeNow(string $timezone): string
    {
        return $this->time
            ->setTimezone(new DateTimeZone($timezone))
            ->format('Y-m-d H:i:s');
    }
}
