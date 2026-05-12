<?php

namespace Modules\Authentication\Application\Actions;

use DateMalformedStringException;
use Modules\Authentication\Application\DTOs\Auth\Output\ForceLogoutTimeOutputDto;
use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;

class CheckLoginTimeAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenIssuerInterface $tokenIssuer,
    ) {
    }

    /**
     * @throws DateMalformedStringException
     */
    public function execute(): ForceLogoutTimeOutputDto
    {
        $authUsers = $this->userRepository->getAuthUsers();

        $logoutUsers = [];
        foreach ($authUsers as $user) {
            if (!$user->verifyLoginTime()) {
                $logoutUsers[] = $user;
                $this->tokenIssuer->revoke($user->id());
            }
        }
        return new ForceLogoutTimeOutputDto($logoutUsers);
    }
}
