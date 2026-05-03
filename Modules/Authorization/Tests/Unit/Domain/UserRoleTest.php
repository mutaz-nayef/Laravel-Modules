<?php

namespace Modules\Authorization\Tests\Unit\Domain;

use Modules\Authentication\Domain\Entities\User;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authorization\Domain\Entities\Role;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{

    private User $testUser;
    private Role $testUserRole;

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
        $this->testUserRole = new Role(
            id: new RoleId(1),
            name: 'admin',
            display_name: 'Admin',
        );
    }

    public function test_it_give_role_to_user(): void
    {
        $this->testUser->assignRole($this->testUserRole);
        $output = $this->testUser->hasRole($this->testUserRole);
        $this->assertTrue($output);
    }

    public function test_it_revoke_role_to_user(): void
    {
        $this->testUser->assignRole($this->testUserRole);
        $this->testUser->revokeRole($this->testUserRole);
        $output = $this->testUser->hasRole($this->testUserRole);
        $this->assertFalse($output);
    }

    public function test_it_sync_roles_to_user(): void
    {
        $this->testUser->assignRole($this->testUserRole);
        $editor = new Role(
            id: new RoleId(2),
            name: 'editor',
            display_name: 'Editor',
        );

        $manager = new Role(
            id: new RoleId(3),
            name: 'manager',
            display_name: 'Manager',
        );
        $this->testUser->syncRoles([$editor, $manager]);

        $output = $this->testUser->hasRole($this->testUserRole);

        $this->assertFalse($output);
    }


    public function tearDown(): void
    {
        parent::tearDown();
    }

}
