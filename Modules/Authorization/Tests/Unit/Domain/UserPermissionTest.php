<?php

namespace Modules\Authorization\Tests\Unit\Domain;

use Modules\Authentication\Domain\Entities\User;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

class UserPermissionTest extends TestCase
{

    private User $testUser;
    private Permission $testUserPermission;

    public function setUp(): void
    {
        $this->testUser = new User(
            id: new UserId(1),
            name: 'test',
            email: new Email('test@example.com'),
            password: HashedPassword::fromPlain('password'),
            isActive: true,
            isEmailVerified: true,
        );
        $this->testUserPermission = new Permission(
            id: new PermissionId(1),
            name: 'post:edit',
            group: 'posts'
        );
    }

    public function test_it_give_permission_to_user(): void
    {
        $this->testUser->givePermissionTo($this->testUserPermission);
        $output = $this->testUser->hasPermissionTo($this->testUserPermission);
        $this->assertTrue($output);
    }

    public function test_it_revoke_permission_to_user(): void
    {
        $this->testUser->givePermissionTo($this->testUserPermission);
        $this->testUser->revokePermissionTo($this->testUserPermission);
        $output = $this->testUser->hasPermissionTo($this->testUserPermission);
        $this->assertFalse($output);
    }

    public function test_it_sync_permission_to_user(): void
    {
        $this->testUser->givePermissionTo($this->testUserPermission);
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

        $this->testUser->syncPermissions([$permissionPostView, $permissionPostDelete]);

        $output = $this->testUser->hasPermissionTo($this->testUserPermission);

        $this->assertFalse($output);
    }


    public function tearDown(): void
    {
        parent::tearDown();
    }

}
