<?php

namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Application\DTOs\Auth\Input\RegisterInputDto;
use Modules\Authentication\Application\DTOs\Auth\Output\AuthOutputDto;
use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Entities\User;
use Modules\Authentication\Domain\Exceptions\EmailAlreadyExistsException;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;

class RegisterUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenIssuerInterface $tokenIssuer,
    ) {
    }


    /**
     * @throws EmailAlreadyExistsException
     */
    public function execute(RegisterInputDto $input): AuthOutputDto
    {
        if ($this->userRepository->findByEmail(new Email($input->email))) {
            throw new EmailAlreadyExistsException('Email already exist', 422);
        }
        $user = User::register(
            name: $input->name,
            email: new Email($input->email),
            password: HashedPassword::fromPlain($input->password),
        );
        $user = $this->userRepository->save($user);

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
