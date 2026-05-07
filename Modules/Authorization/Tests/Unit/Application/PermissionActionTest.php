<?php

namespace Modules\Authorization\Tests\Unit\Application;

use Mockery;
use Mockery\MockInterface;
use Modules\Authorization\Application\Actions\Permission\PermissionDestroyAction;
use Modules\Authorization\Application\Actions\Permission\PermissionStoreAction;
use Modules\Authorization\Application\Actions\Permission\PermissionUpdateAction;
use Modules\Authorization\Application\DTOs\Input\Permission\BasePermissionInputDto;
use Modules\Authorization\Application\DTOs\Input\Permission\PermissionStoreInputDto;
use Modules\Authorization\Application\DTOs\Input\Permission\PermissionUpdateInputDto;
use Modules\Authorization\Domain\Contracts\PermissionRepositoryInterface;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Tests\TestCase;

//use PHPUnit\Framework\TestCase;

class PermissionActionTest extends TestCase
{

    private PermissionRepositoryInterface|MockInterface $permissionRepository;
    private PermissionStoreAction $permissionStoreAction;
    private PermissionUpdateAction $permissionUpdateAction;
    private PermissionDestroyAction $permissionDestroyAction;


    public function setUp(): void
    {
        parent::setUp();

        $this->permissionRepository = Mockery::mock(PermissionRepositoryInterface::class);

        $this->permissionUpdateAction = new PermissionUpdateAction(
            $this->permissionRepository,
        );

        $this->permissionStoreAction = new PermissionStoreAction(
            $this->permissionRepository,
        );

        $this->permissionDestroyAction = new PermissionDestroyAction(
            $this->permissionRepository,
        );
    }

    public function test_it_create_permission_and_return_dto(): void
    {
        $permissionId = new PermissionId(1);
        $permission = new Permission(
            id: $permissionId,
            name: 'post:edit',
            group: 'posts'
        );

        $this->permissionRepository->shouldReceive('save')->once()->andReturn($permission);

        $output = $this->permissionStoreAction->execute(
            new PermissionStoreInputDto('post:edit', 'posts')
        );

        $this->assertSame('post:edit', $output->name);
        $this->assertSame('posts', $output->group);
    }


    public function test_it_update_permission_and_return_dto(): void
    {

        $permissionId = new PermissionId(1);
        $permission = new Permission(
            id: $permissionId,
            name: 'post:edit',
            group: 'posts'
        );
        $this->permissionRepository->shouldReceive('findById')->once()->andReturn($permission);

        $updated = new Permission(
            id: $permissionId,
            name: 'post:view',
            group: 'posts'
        );

        $this->permissionRepository
            ->shouldReceive('save')
            ->once()
            ->andReturn($updated);
        $output = $this->permissionUpdateAction->execute(
            new PermissionUpdateInputDto(
                permissionId: $permissionId,
                name: 'post:view',
                group: 'posts'
            )
        );

        $this->assertSame('post:view', $output->name);
        $this->assertSame('posts', $output->group);
    }

    public function test_it_delete_permission_successfully(): void
    {
        $permissionId = new PermissionId(1);

        $this->permissionRepository->shouldReceive('findById')->once()->andReturn(
            new Permission(
                id: $permissionId,
                name: 'post:edit',
                group: 'posts'
            )
        );
        $this->permissionRepository->shouldReceive('delete')->with($permissionId)->once();

        $this->permissionDestroyAction->execute(
            new BasePermissionInputDto($permissionId)
        );
    }

}
