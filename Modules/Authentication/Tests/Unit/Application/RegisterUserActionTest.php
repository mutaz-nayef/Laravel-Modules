<?php

namespace Modules\Authentication\Tests\Unit\Application;

use Illuminate\Foundation\Testing\TestCase;
use Mockery;
use Mockery\MockInterface;
use Modules\Authentication\Application\Actions\RegisterUserAction;
use Modules\Authentication\Application\DTOs\Auth\Input\RegisterInputDto;
use Modules\Authentication\Domain\Contracts\EmailVerificationInterface;
use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Entities\User;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authentication\Domain\ValueObjects\IssuedToken;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Entities\Role;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\Shared\Infrastructure\Events\EventDispatcher;

class RegisterUserActionTest extends TestCase
{
    private UserRepositoryInterface|MockInterface $userRepository;
    private RoleRepositoryInterface|MockInterface $roleRepository;
    private TokenIssuerInterface|MockInterface $tokenIssuer;
    private EmailVerificationInterface|MockInterface $emailVerification;
    private EventDispatcher $eventDispatcher;

    private RegisterUserAction $action;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->roleRepository = Mockery::mock(RoleRepositoryInterface::class);
        $this->tokenIssuer = Mockery::mock(TokenIssuerInterface::class);
        $this->emailVerification = Mockery::mock(EmailVerificationInterface::class);
        $this->eventDispatcher = new EventDispatcher;
        $this->action = new RegisterUserAction(
            $this->userRepository,
            $this->tokenIssuer,
            $this->roleRepository,
            $this->emailVerification,
            $this->eventDispatcher
        );
    }


    public function test_user_successfully_register_return_dto(): void
    {
        $userId = new UserId(1);
        $user = new User(
            id: $userId,
            name: 'user test',
            email: new Email('john@example.com'),
            password: HashedPassword::fromPlain('password'),
            isActive: true,
            isEmailVerified: true,
        );

        $roleUser = new Role(new RoleId(1), 'user', 'user');

        $this->userRepository->shouldReceive('findByEmail')->once()->andReturn(null);
        $this->roleRepository->shouldReceive('findByName')->with('user')->once()->andReturn($roleUser);
        $this->userRepository->shouldReceive('save')->with(Mockery::type(User::class))->once()->andReturn($user);
        $this->emailVerification->shouldReceive('sendEmailVerification')->once()->andReturn(null);
        $this->tokenIssuer
            ->shouldReceive('issue')
            ->twice()
            ->andReturn(
                new IssuedToken('access_token'),
                new IssuedToken('refresh_token')
            );

        $output = $this->action->execute(new RegisterInputDto('user test', 'john@example.com', 'password'));


        $this->assertSame('john@example.com', $output->email);
        $this->assertSame('access_token', $output->accessToken);
        $this->assertSame('refresh_token', $output->refreshToken);
        $this->assertSame('Bearer', $output->tokenType);

    }


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
