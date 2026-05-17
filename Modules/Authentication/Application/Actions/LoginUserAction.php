<?php

namespace Modules\Authentication\Application\Actions;

use DateMalformedStringException;
use Modules\Authentication\Application\DTOs\Auth\Input\LoginInputDto;
use Modules\Authentication\Application\DTOs\Auth\Output\AuthOutputDto;
use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\InvalidCredentialsException;
use Modules\Authentication\Domain\Exceptions\LoginNotAllowedThisTimeException;
use Modules\Authentication\Domain\Exceptions\UserNotActiveException;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Shared\Abstractions\EventDispatcherInterface;


class LoginUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenIssuerInterface $tokenIssuer,
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly EventDispatcherInterface $dispatcher,
    ) {
    }

    /**
     * @param  LoginInputDto  $input
     * @return AuthOutputDto
     * @throws InvalidCredentialsException
     * @throws LoginNotAllowedThisTimeException
     * @throws UserNotActiveException
     * @throws DateMalformedStringException
     */
    public function execute(LoginInputDto $input): AuthOutputDto
    {
        // First: find user by email
        $user = $this->userRepository->findByEmail(new Email($input->email));
        if (!$user) {
            throw new InvalidCredentialsException('Invalid credentials. please try again.', 401);
        }
        // Domain enforces business rules
        $user->login($input->password);

        $events = $user->pullDomainEvents();

        foreach ($events as $event) {
            $this->dispatcher->dispatch($event);
        }

        $roles = $this->roleRepository->findByUserId($user->id());
        if ($roles) {
            foreach ($roles as $role) {
                $user->assignRole($role);
            }
        }

        // generate tokens
        $access_token = $this->tokenIssuer->issue($user->id(), 'access_token');
        $refresh_token = $this->tokenIssuer->issue($user->id(), 'refresh_token', 60 * 24 * 30);

        // return output dto
        return new AuthOutputDto(
            userId: $user->id()->value(),
            name: $user->name(),
            email: $user->email(),
            accessToken: $access_token->plainText(),
            refreshToken: $refresh_token->plainText(),
            tokenType: 'Bearer',
            roles: $user->roles(),
            permissions: array_map(
                fn(Permission $p) => $p->name(),
                $user?->permissions() ?? []
            ),
        );

    }
}
