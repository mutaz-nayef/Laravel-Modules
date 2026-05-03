<?php

namespace Modules\Authorization\Tests\Unit\Domain;

use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\Entities\Role;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use PHPUnit\Framework\TestCase;

class RolePermissionTest extends TestCase
{

    private Role $testRole;
    private Permission $testRolePermission;

    public function setUp(): void
    {
        $this->testRole = new Role(
            id: new RoleId(1),
            name: 'admin',
            display_name: 'Administrator',
        );
        $this->testRolePermission = new Permission(
            id: new PermissionId(1),
            name: 'post:edit',
            group: 'posts'
        );
    }

    public function test_it_give_permission_to_role(): void
    {
        $this->testRole->givePermissionTo($this->testRolePermission);
        $output = $this->testRole->hasPermissionTo($this->testRolePermission);
        $this->assertTrue($output);
    }

    public function test_it_revoke_permission_to_user(): void
    {
        $this->testRole->givePermissionTo($this->testRolePermission);
        $this->testRole->revokePermissionTo($this->testRolePermission);
        $output = $this->testRole->hasPermissionTo($this->testRolePermission);
        $this->assertFalse($output);
    }

    public function test_it_sync_permission_to_user(): void
    {
        $this->testRole->givePermissionTo($this->testRolePermission);
        $permissionPostView = new Permission(
            id: new PermissionId(2),
            name: 'post:view',
            group: 'posts'
        );
        $permissionPostDelete = new Permission(
            id: new PermissionId(3),
            name: 'post:delete',
            group: 'posts'
        );

        $this->testRole->syncPermissions([$permissionPostView, $permissionPostDelete]);

        $output = $this->testRole->hasPermissionTo($this->testRolePermission);

        $this->assertFalse($output);
    }


    public function tearDown(): void
    {
        parent::tearDown();
    }

}
