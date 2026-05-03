<?php

namespace Modules\Authorization\Tests\Unit\Application;

use Mockery;
use Mockery\MockInterface;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Entities\User;
use Modules\Authentication\Domain\Exceptions\UserNotFoundException;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authorization\Application\Actions\UserPermission\UserPermissionGetAction;
use Modules\Authorization\Application\DTOs\Input\UserPermission\UserPermissionGetInputDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Shared\Domain\ValueObjects\UserId;
use Tests\TestCase;

//use PHPUnit\Framework\TestCase;

class UserPermissionActionTest extends TestCase
{

    private UserRepositoryInterface|MockInterface $userRepository;
    private PermissionRepositoryInterface|MockInterface $permissionRepository;
    private UserPermissionGetAction $userPermissionGetAction;


    public function setUp(): void
    {
        parent::setUp();

        $this->permissionRepository = Mockery::mock(PermissionRepositoryInterface::class);
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);

        $this->userPermissionGetAction = new UserPermissionGetAction(
            $this->userRepository,
            $this->permissionRepository
        );

    }

    public function test_it_get_permissions_for_user(): void
    {
        $userId = new UserId(1);

        $permission = new Permission(
            id: new PermissionId(1),
            name: 'posts:edit',
            group: 'posts'
        );

        $user = new User(
            name: 'John Doe',
            email: new Email('john@test.com'),
            password: HashedPassword::fromPlain('password'),
            isActive: true,
            isEmailVerified: true,
            id: $userId,
        );

        // IMPORTANT: source of truth only from repository
        $this->userRepository
            ->shouldReceive('findById')
            ->once()
            ->with($userId)
            ->andReturn($user);

        $this->permissionRepository
            ->shouldReceive('findByUserId')
            ->once()
            ->with($userId)
            ->andReturn([$permission]);

        $output = $this->userPermissionGetAction->execute(
            new UserPermissionGetInputDto($userId)
        );

        // Assert
        $this->assertCount(1, $output->permissions);
        $this->assertSame('posts:edit', $output->permissions[0]->name());
    }

    public function test_it_throws_when_user_not_found(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->userRepository->shouldReceive('findById')->once()->andReturn(null);

        $this->userPermissionGetAction->execute(new UserPermissionGetInputDto(new UserId(1)));
    }

}
