<?php

namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Application\DTOs\Auth\Output\AuthOutputDto;
use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\EmailAlreadyExistsException;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;

class RefreshTokenAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenIssuerInterface $tokenIssuer,

    ) {
    }


    /**
     * @throws EmailAlreadyExistsException
     * @throws RoleNotFoundException
     */

    public function execute(string $refreshToken): AuthOutputDto
    {


        $tokens = $this->tokenIssuer->issue($user->id());

        $this->emailVerification->sendEmailVerification($user->id());

        // return output dto
        return new AuthOutputDto(
            userId: $user->id()->value(),
            name: $user->name(),
            email: $user->email(),
            accessToken: $tokens['access_token']->plainText(),
            refreshToken: $tokens['refresh_token']->plainText(),
            tokenType: 'Bearer',
            roles: $user->roles(),
            permissions: array_map(
                fn(Permission $p) => $p->name(),
                $user?->permissions() ?? []
            ),
        );
    }
}
