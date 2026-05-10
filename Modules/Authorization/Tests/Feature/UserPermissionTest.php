<?php

namespace Modules\Authorization\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Modules\Authorization\Infrastructure\Database\Seeders\RolesAndPermissionsSeeder;
use Modules\Authorization\Infrastructure\Models\PermissionModel;
use Modules\Authorization\Infrastructure\Models\RoleModel;
use Tests\TestCase;

class UserPermissionTest extends TestCase
{
    use RefreshDatabase;

    private UserModel $user;
    private RoleModel $role;
    private $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->role = RoleModel::where('name', 'admin')->first();
        $this->user = UserModel::factory()->create(['password' => bcrypt('password123')]);

        $this->user->roles()->attach($this->role);

        $this->token = $this->user->createToken('Personal Access Token')->plainTextToken;

    }

    public function test_create_permission_for_user_successfully(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->postJson('/api/users/1/permissions', [
            "permissions" => ["home:view"]
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'home:view',
        ]);
    }


    public function test_sync_permissions_for_user(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->putJson('/api/users/1/permissions', [
            "permissions" => ["home:view"]
        ]);
        $permission = PermissionModel::where('name', 'home:view')->first();
        $response->assertStatus(200);
        $this->assertEquals(1, $this->user->permissions()->count());
        $this->assertDatabaseHas('user_permissions', [
            'user_id' => $this->user->id,
            'permission_id' => $permission->id,
        ]);
    }


    public function test_bulk_remove_permissions_for_user(): void
    {
        $permission = PermissionModel::where('name', 'home:view')->first();
        $this->user->permissions()->attach($permission);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->deleteJson("/api/users/{$this->user->id}/permissions", [
            "permissions" => ["home:view"]
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('user_permissions', [
            'user_id' => $this->user->id,
            'permission_id' => $permission->id,
        ]);
    }

    public function test_delete_permissions_for_user(): void
    {
        $permission = PermissionModel::where('name', 'home:view')->first();
        $this->user->permissions()->attach($permission);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->deleteJson("/api/users/{$this->user->id}/permissions/{$permission->id}", [
            "permissions" => ["home:view"]
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('user_permissions', [
            'user_id' => $this->user->id,
            'permission_id' => $permission->id,
        ]);
    }

    public function test_throws_404_permission_not_found_for_user(): void
    {
        $permission = PermissionModel::find(1);
        $user = RoleModel::create([
            'name' => 'test',
            'display_name' => 'Test',
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->deleteJson("/api/users/{$this->user->id}/permissions/".$permission->id);

        $response->assertStatus(404);
    }

}
