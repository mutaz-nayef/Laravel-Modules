<?php

namespace Modules\Authentication\Tests\Unit\Application;

use Illuminate\Foundation\Testing\TestCase;
use Mockery;
use Mockery\MockInterface;
use Modules\Authentication\Application\Actions\LoginUserAction;
use Modules\Authentication\Application\DTOs\Auth\Input\LoginInputDto;
use Modules\Authentication\Domain\Contracts\TokenIssuerInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Entities\User;
use Modules\Authentication\Domain\Exceptions\InvalidCredentialsException;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authentication\Domain\ValueObjects\IssuedToken;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Entities\Role;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Shared\Domain\ValueObjects\UserId;
use Modules\Shared\Infrastructure\Events\EventDispatcher;

class LoginUserActionTest extends TestCase
{
    private UserRepositoryInterface|MockInterface $userRepository;
    private RoleRepositoryInterface|MockInterface $roleRepository;
    private TokenIssuerInterface|MockInterface $tokenIssuer;
    private EventDispatcher $dispatcher;

    private LoginUserAction $action;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->roleRepository = Mockery::mock(RoleRepositoryInterface::class);
        $this->tokenIssuer = Mockery::mock(TokenIssuerInterface::class);
        $this->dispatcher = new EventDispatcher;
        $this->action = new LoginUserAction(
            $this->userRepository,
            $this->tokenIssuer,
            $this->roleRepository,
            $this->dispatcher,
        );
    }

    public function test_user_successfully_logged_in_return_dto(): void
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

        $role = new Role(new RoleId(1), 'admin', 'admin');

        $this->userRepository->shouldReceive('findByEmail')->once()->andReturn($user);
        $this->roleRepository->shouldReceive('findByUserId')->once()->andReturn([$role]);
        $this->tokenIssuer
            ->shouldReceive('issue')
            ->twice()
            ->andReturn(
                new IssuedToken('access_token'),
                new IssuedToken('refresh_token')
            );
        $output = $this->action->execute(new LoginInputDTO('john@example.com', 'password'));

        $this->assertSame('john@example.com', $output->email);
        $this->assertSame('access_token', $output->accessToken);
        $this->assertSame('refresh_token', $output->refreshToken);
        $this->assertSame('Bearer', $output->tokenType);
        $this->assertSame([$role], $output->roles);
    }

    public function test_throws_when_user_not_found(): void
    {
        $this->expectException(InvalidCredentialsException::class);

        $this->userRepository->shouldReceive('findByEmail')->once()->andReturn(null);

        $this->action->execute(new LoginInputDto('nobody@example.com', 'password'));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
