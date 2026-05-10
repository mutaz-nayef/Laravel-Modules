<?php

namespace Modules\Authentication\Domain\Contracts;

use Modules\Authentication\Domain\ValueObjects\IssuedToken;
use Modules\Shared\Domain\ValueObjects\UserId;

interface TokenIssuerInterface
{

    public function issue(
        UserId $userId,
        string $name,
        ?string $expiresAtInMinutes = null,
        ?array $attributes = null
    ): IssuedToken;

    public function refresh($refreshToken): IssuedToken;

    public function revoke(UserId $userId): void;

}
