<?php

namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Application\DTOs\Auth\Input\LoginInputDto;
use Modules\Authentication\Application\DTOs\Auth\Output\AuthOutputDto;
use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\EmailNotVerifiedException;
use Modules\Authentication\Domain\Exceptions\InvalidCredentialsException;
use Modules\Authentication\Domain\Exceptions\LoginNotAllowedThisTimeException;
use Modules\Authentication\Domain\Exceptions\UserNotActiveException;
use Modules\Authentication\Domain\ValueObjects\Email;


class LoginUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenIssuerInterface $tokenIssuer,
    ) {
    }

    /**
     * @throws EmailNotVerifiedException
     * @throws LoginNotAllowedThisTimeException
     * @throws UserNotActiveException
     * @throws InvalidCredentialsException
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

        #Fix:
        // load the role for user
//        $role = $this->roleRepository->findByUserId($user->id());
//        if($role)
//        {
//           $user->assignRole($role);
//        }

        // generate token
        $token = $this->tokenIssuer->issue($user->id());

        // return output dto
        return new AuthOutputDto(
            userId: $user->id()->value(),
            name: $user->name(),
            email: $user->email(),
            token: $token->plainText(),
            tokenType: 'Bearer',
            roleName: 'guest',
            permissions: []
        );

    }
}
