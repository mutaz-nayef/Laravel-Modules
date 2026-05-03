<?php

namespace Modules\Authentication\Tests\Unit\Application;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;
use Mockery\MockInterface;
use Modules\Authentication\Application\Actions\RegisterUserAction;
use Modules\Authentication\Application\DTOs\Auth\Input\RegisterInputDto;
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

class RegisterUserActionTest extends BaseTestCase
{
    private UserRepositoryInterface|MockInterface $userRepository;
    private RoleRepositoryInterface|MockInterface $roleRepository;
    private TokenIssuerInterface|MockInterface $tokenIssuer;

    private RegisterUserAction $action;

    public function setUp(): void
    {
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->roleRepository = Mockery::mock(RoleRepositoryInterface::class);
        $this->tokenIssuer = Mockery::mock(TokenIssuerInterface::class);

        $this->action = new RegisterUserAction(
            $this->userRepository,
            $this->tokenIssuer,
            $this->roleRepository,
        );
    }

    // Fix
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
        $roleAdmin = new Role(new RoleId(2), 'admin', 'admin');

        $this->userRepository->shouldReceive('findByEmail')->once()->andReturn(null);
        $this->roleRepository->shouldReceive('findByName')->with('user')->once()->andReturn($roleUser);
        $this->roleRepository->shouldReceive('findByName')->with('admin')->once()->andReturn($roleAdmin);
        $this->userRepository->shouldReceive('save')->with(Mockery::type(User::class))->once()->andReturn($user);
//        $this->userRepository
//            ->shouldReceive('save')
//            ->once()
//            ->with(Mockery::on(function ($user) {
//                return true;
//            }))
//            ->andReturn($user);
        $this->tokenIssuer->shouldReceive('issue')->once()->andReturn(new IssuedToken('token-abc'));

        $output = $this->action->execute(new RegisterInputDto('user test', 'john@example.com', 'password'));


        $this->assertSame('john@example.com', $output->email);
        $this->assertSame('token-abc', $output->token);
        $this->assertSame('Bearer', $output->tokenType);
//
//        $this->assertEqualsCanonicalizing(
//            ['user', 'admin'],
//            array_map(fn($role) => $role->name(), $output->roles)
//        );
    }


    protected function tearDown(): void
    {
        Mockery::close();
    }
}
