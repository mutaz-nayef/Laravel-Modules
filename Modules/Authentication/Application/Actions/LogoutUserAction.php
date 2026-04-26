<?php

namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Application\DTO\Auth\Input\LogoutInputDto;
use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;

final class LogoutUserAction
{
    public function __construct(
        private readonly TokenIssuerInterface $tokenIssuer,
    ) {
    }

    public function execute(LogoutInputDto $input): void
    {
        $this->tokenIssuer->revoke($input->userId);
    }
}
