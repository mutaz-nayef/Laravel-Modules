<?php

namespace Modules\Authorization\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Modules\Authorization\Infrastructure\Database\Seeders\RolesAndPermissionsSeeder;
use Modules\Authorization\Infrastructure\Models\RoleModel;
use Tests\TestCase;

class UserRoleTest extends TestCase
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

    public function test_create_role_for_user_successfully(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->postJson('/api/users/1/roles', [
            "roles" => ["editor"]
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'editor',
        ]);
    }


    public function test_sync_roles_for_user(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->putJson('/api/users/1/roles', [
            "roles" => ["admin"]
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, $this->user->roles()->count());
        $this->assertDatabaseHas('user_roles', [
            'user_id' => $this->user->id,
            'role_id' => $this->role->id,
        ]);
    }


    public function test_bulk_remove_roles_for_user(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->deleteJson("/api/users/{$this->user->id}/roles", [
            "roles" => ["admin"]
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('user_roles', [
            'user_id' => $this->user->id,
            'role_id' => $this->role->id,
        ]);
    }

    public function test_delete_roles_for_user(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->deleteJson("/api/users/{$this->user->id}/roles/{$this->role->id}", [
            "roles" => ["home:view"]
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('user_roles', [
            'user_id' => $this->user->id,
            'role_id' => $this->role->id,
        ]);
    }

    public function test_throws_404_role_not_found_for_user(): void
    {

        $user = UserModel::factory()->create(['password' => bcrypt('password123')]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->deleteJson("/api/users/{$user->id}/roles/".$this->role->id);

        $response->assertStatus(404);
    }

}
