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
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;

class RegisterUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenIssuerInterface $tokenIssuer,
        private readonly RoleRepositoryInterface $roleRepository,
    ) {
    }


    /**
     * @throws EmailAlreadyExistsException
     * @throws RoleNotFoundException
     */

    public function execute(RegisterInputDto $input): AuthOutputDto
    {
        if ($this->userRepository->findByEmail(new Email($input->email))) {
            throw new EmailAlreadyExistsException('Email already exist', 422);
        }

        $user = new User(
            id: null,
            name: $input->name,
            email: new Email($input->email),
            password: HashedPassword::fromPlain($input->password),
            isActive: true,
            isEmailVerified: false,
            roles: [],
            permissions: [],
        );

        $roleUser = $this->roleRepository->findByName('user');
        $roleAdmin = $this->roleRepository->findByName('admin');

        if (!$roleUser || !$roleAdmin) {
            throw new RoleNotFoundException('Role not found.', 404);
        }

        $user->assignRole($roleUser);
        $user->assignRole($roleAdmin);
        $user = $this->userRepository->save($user);
        //  $user = $this->userRepository->saveRoles($user);
        // generate token

        $token = $this->tokenIssuer->issue($user->id());

        // return output dto
        return new AuthOutputDto(
            userId: $user->id()->value(),
            name: $user->name(),
            email: $user->email(),
            token: $token->plainText(),
            tokenType: 'Bearer',
            roles: $user->roles(),
            permissions: $user->permissions(),
        );
    }
}
