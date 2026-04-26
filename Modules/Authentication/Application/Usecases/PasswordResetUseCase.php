<?php

namespace Modules\Authentication\Application\Usecases;

use Modules\Authentication\Application\DTO\Auth\Input\PasswordResetInputDto;
use Modules\Authentication\Domain\Contracts\PasswordResetInterface;
use Modules\Authentication\Domain\Exceptions\InvalidArgumentException;
use Modules\Authentication\Domain\ValueObjects\Email;

class PasswordResetUseCase
{
    public function __construct(protected PasswordResetInterface $passwordReset)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function execute(PasswordResetInputDto $dto): string
    {
        $email = new Email($dto->email);

        $dto->email = $email->value;

        return $this->passwordReset->reset($dto->toArray());
    }

}
