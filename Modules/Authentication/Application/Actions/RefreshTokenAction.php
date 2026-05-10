<?php

namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\ValueObjects\IssuedToken;

class RefreshTokenAction
{
    public function __construct(
        private readonly TokenIssuerInterface $tokenIssuer,
    ) {
    }

    public function execute(string $refreshToken): IssuedToken
    {
        return $this->tokenIssuer->refresh($refreshToken);
    }
}
