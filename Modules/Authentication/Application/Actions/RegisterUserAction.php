<?php

namespace Modules\Authentication\Application\Actions;

use Modules\Authentication\Application\DTOs\Auth\Input\RegisterInputDto;
use Modules\Authentication\Application\DTOs\Auth\Output\AuthOutputDto;
use Modules\Authentication\Domain\Contracts\EmailVerificationInterface;
use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Entities\User;
use Modules\Authentication\Domain\Events\UserRegistered;
use Modules\Authentication\Domain\Exceptions\EmailAlreadyExistsException;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\Exceptions\RoleNotFoundException;
use Modules\Notifications\Domain\Contracts\NotificationServiceInterface;
use Modules\Shared\Abstractions\EventDispatcherInterface;

class RegisterUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenIssuerInterface $tokenIssuer,
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly EmailVerificationInterface $emailVerification,
        private readonly EventDispatcherInterface $dispatcher,
        private readonly NotificationServiceInterface $notificationService,
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

        if (!$roleUser) {
            throw new RoleNotFoundException('Role not found.', 404);
        }

        $user->assignRole($roleUser);

        $user = $this->userRepository->save($user);

        // generate tokens
        $access_token = $this->tokenIssuer->issue($user->id(), 'access_token');
        $refresh_token = $this->tokenIssuer->issue($user->id(), 'refresh_token', 60 * 24 * 30);

        $this->emailVerification->sendEmailVerification($user->id());

        $this->dispatcher->dispatch(new UserRegistered($user->id()));

//        $notification = new Notification();
        
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
