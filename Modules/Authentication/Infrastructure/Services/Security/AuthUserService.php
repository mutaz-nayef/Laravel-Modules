<?php

namespace Modules\Authentication\Infrastructure\Services\Security;


use Modules\Authentication\Domain\Contracts\AuthInterface;
use Modules\Authentication\Domain\Contracts\PasswordHasherInterface;
use Modules\Authentication\Domain\Contracts\PasswordVerifierInterface;
use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Exceptions\InvalidCredentialsException;
use Modules\Authentication\Domain\Exceptions\YouCannotStayLoggedInAtThisTime;
use Modules\Authentication\Domain\Services\LoginPolicy;
use Modules\Authentication\Infrastructure\DataMapper\UserMapper;
use Modules\Shared\Domain\Exceptions\NotFoundException;

class AuthUserService implements AuthInterface
{
    public function __construct(
        protected LoginPolicy $loginPolicy,
        protected UserRepositoryInterface $userRepository,
        protected PasswordVerifierInterface $passwordVerifier,
        protected PasswordHasherInterface $passwordHasher,
        protected TokenIssuerInterface $token,
        protected UserMapper $userMapper,
    ) {
    }




    public function register($attributes): array
    {
        $attributes->password = $this->passwordHasher->make($attributes->password);

        $user = $this->userRepository->register($attributes->toArray());

        $token = $this->token->issue($this->userMapper->toModel($user));
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * @throws YouCannotStayLoggedInAtThisTime
     */
    public function ensureUserCanStayLoggedIn($user): bool
    {
        if (!$this->loginPolicy->canStayLoggedIn()) {
            $this->logout($user);
            throw new YouCannotStayLoggedInAtThisTime('UserModel cannot stay logged in at this time', 403);
        }
        return true;
    }

    public function logout($user): bool
    {
        return $this->token->revoke($user);
    }
}
