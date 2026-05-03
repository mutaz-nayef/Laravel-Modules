<?php

namespace Modules\Authorization\Tests\Unit\Application;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Modules\Authorization\Application\Actions\Role\RoleDestroyAction;
use Modules\Authorization\Application\Actions\Role\RoleStoreAction;
use Modules\Authorization\Application\Actions\Role\RoleUpdateAction;
use Modules\Authorization\Application\DTOs\Input\Role\BaseRoleInputDto;
use Modules\Authorization\Application\DTOs\Input\Role\RoleStoreInputDto;
use Modules\Authorization\Application\DTOs\Input\Role\RoleUpdateInputDto;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Entities\Role;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Tests\TestCase;

class RoleActionTest extends TestCase
{
    use RefreshDatabase;

    private RoleRepositoryInterface|MockInterface $roleRepository;
    private RoleStoreAction $roleStoreAction;
    private RoleUpdateAction $roleUpdateAction;
    private RoleDestroyAction $roleDestroyAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->roleRepository = Mockery::mock(RoleRepositoryInterface::class);

        $this->roleUpdateAction = new RoleUpdateAction(
            $this->roleRepository,
        );

        $this->roleStoreAction = new RoleStoreAction(
            $this->roleRepository,
        );

        $this->roleDestroyAction = new RoleDestroyAction(
            $this->roleRepository,
        );
    }

    public function test_it_create_role_and_return_dto(): void
    {
        $roleId = new RoleId(1);
        $role = new Role(
            id: $roleId,
            name: 'admin',
            display_name: 'Admin'
        );

        $this->roleRepository->shouldReceive('save')->once()->andReturn($role);

        $output = $this->roleStoreAction->execute(
            new RoleStoreInputDto('admin', 'Admin')
        );

        $this->assertSame('admin', $output->name);
        $this->assertSame('Admin', $output->display_name);
    }


    public function test_it_update_role_and_return_dto(): void
    {

        $roleId = new RoleId(1);
        $role = new Role(
            id: $roleId,
            name: 'admin',
            display_name: 'Admin'
        );
        $this->roleRepository->shouldReceive('findById')->once()->andReturn($role);

        $updated = new Role(
            id: $roleId,
            name: 'manager',
            display_name: 'Manager'
        );
        $this->roleRepository
            ->shouldReceive('save')
            ->once()
            ->andReturn($updated);
        $output = $this->roleUpdateAction->execute(
            new RoleUpdateInputDto(
                roleId: $roleId,
                name: 'manager',
                display_name: 'Manager'
            )
        );

        $this->assertSame('manager', $output->name);
        $this->assertSame('Manager', $output->display_name);
    }

    //Fix
    public function test_it_delete_role_successfully(): void
    {
        $role = new Role(
            id: new RoleId(1),
            name: 'manager',
            display_name: 'Manager'
        );

        $this->roleRepository
            ->shouldReceive('findById')
            ->once()
            ->with(Mockery::type(RoleId::class))
            ->andReturn($role);

        $this->roleRepository
            ->shouldReceive('delete')
            ->once()
            ->with(Mockery::on(fn($id) => $id instanceof RoleId));

        $this->roleDestroyAction->execute(
            new BaseRoleInputDto(new RoleId(1))
        );

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id()->value(),
        ]);
    }

}
