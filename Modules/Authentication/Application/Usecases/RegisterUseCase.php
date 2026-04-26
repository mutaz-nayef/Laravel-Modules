<?php

namespace Modules\Authentication\Application\Usecases;

use Modules\Authentication\Application\DTO\Auth\Input\RegisterInputDto;
use Modules\Authentication\Application\DTO\Auth\Output\AuthOutputDto;
use Modules\Authentication\Domain\Contracts\AuthInterface;
use Modules\Authentication\Domain\Events\UserRegistered;
use Modules\Authentication\Domain\Exceptions\InvalidArgumentException;
use Modules\Authentication\Domain\ValueObjects\Email;

class RegisterUseCase
{
    public function __construct(
        protected AuthInterface $authService,

    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function execute(RegisterInputDto $dto): AuthOutputDto
    {
        $email = new Email($dto->email);

        $result = $this->authService->register($dto);

        event(new UserRegistered($result['user']));

        return new AuthOutputDto($result['user'], $result['token']);
    }

}
