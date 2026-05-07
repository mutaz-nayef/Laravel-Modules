<?php

namespace Modules\Authorization\Tests\Unit\Domain;

use Mockery;
use Modules\Authorization\Domain\Contracts\RoleRepositoryInterface;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\Entities\Role;
use Modules\Authorization\Domain\Services\PolicyEngine;
use Modules\Authorization\Domain\ValueObjects\PermissionId;
use Modules\Authorization\Domain\ValueObjects\PolicyConditions;
use Modules\Authorization\Domain\ValueObjects\ResourceAttributes;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

class PolicyEngineTest extends TestCase
{

    public function test_allows_when_permission_exists_with_no_conditions(): void
    {
        $permission = $this->makePermission('posts:view');
        $engine = $this->makeEngine($this->makeRole([$permission]));

        $result = $engine->evaluate(new UserId(1), $permission, ResourceAttributes::empty());

        $this->assertTrue($result);

    }

    private function makePermission(string $name, array $conditions = []): Permission
    {
        return new Permission(
            new PermissionId(1),
            $name,
            'posts',
            new PolicyConditions($conditions),
        );
    }

    private function makeEngine(Role $role): PolicyEngine
    {
        $repo = Mockery::mock(RoleRepositoryInterface::class);
        $repo->shouldReceive('findByUserId')->andReturn([$role]);
        return new PolicyEngine($repo);
    }

    // --- No conditions: plain RBAC ---

    private function makeRole(array $permissions): Role
    {
        return new Role(new RoleId(1), 'editor', 'Editor', $permissions);
    }

    // --- owner_only: user IS the owner ---

    public function test_allows_owner_when_owner_only_conditions(): void
    {
        $permission = $this->makePermission('posts:view', ['owner_only' => true]);
        $engine = $this->makeEngine($this->makeRole([$permission]));
        $result = $engine->evaluate(new UserId(1), $permission,
            ResourceAttributes::from(['owner_id' => 1]));

        $this->assertTrue($result);  // ← same as userId
    }

    // --- owner_only: user IS not the owner ---
    public function test_denies_owner_when_owner_only_conditions(): void
    {
        $permission = $this->makePermission('posts:view', ['owner_only' => true]);
        $engine = $this->makeEngine($this->makeRole([$permission]));
        $result = $engine->evaluate(new UserId(1), $permission,
            ResourceAttributes::from(['owner_id' => 4]));

        $this->assertFalse($result);  // ← not same userId
    }

    // --- allowed statuses ---
    public function test_allows_when_resource_status_is_in_allowed_statuses(): void
    {
        $permission = $this->makePermission('posts:view', ['allowed_statuses' => ['published']]);
        $engine = $this->makeEngine($this->makeRole([$permission]));
        $result = $engine->evaluate(new UserId(1), $permission,
            ResourceAttributes::from(['status' => 'published']));

        $this->assertTrue($result);  // ← allowed statuses
    }

    // --- not allowed statuses ---
    public function test_denies_when_resource_status_is_not_in_allowed_statuses(): void
    {
        $permission = $this->makePermission('posts:view', ['allowed_statuses' => ['published']]);
        $engine = $this->makeEngine($this->makeRole([$permission]));
        $result = $engine->evaluate(new UserId(1), $permission,
            ResourceAttributes::from(['status' => 'draft']));

        $this->assertFalse($result);  // ← not allowed statuses
    }

    // --- No Role ---
    public function test_denies_when_user_has_no_role(): void
    {
        $repo = Mockery::mock(RoleRepositoryInterface::class);
        $repo->shouldReceive('findByUserId')->andReturn(null);
        $engine = new PolicyEngine($repo);
        $permission = $this->makePermission('posts:edit');

        $result = $engine->evaluate(
            new UserId(1),
            $permission,
            ResourceAttributes::empty(),
        );

        $this->assertFalse($result);
    }

    //--- Permission not in Role ---
    public function test_denies_when_permission_not_assigned_to_role(): void
    {
        $permission = $this->makePermission('posts:edit');
        $repo = Mockery::mock(RoleRepositoryInterface::class);
        $repo->shouldReceive('findByUserId')->andReturn(null);
        $engine = new PolicyEngine($repo);

        $result = $engine->evaluate(
            new UserId(1),
            $permission,
            ResourceAttributes::empty(),
        );

        $this->assertFalse($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
