<?php

namespace Modules\Authorization\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Modules\Authorization\Infrastructure\Database\Seeders\RolesAndPermissionsSeeder;
use Modules\Authorization\Infrastructure\Models\RoleModel;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    private UserModel $user;
    private $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $role = RoleModel::where('name', 'admin')->first();
        $this->user = UserModel::factory()->create(['password' => bcrypt('password123')]);

        $this->user->roles()->attach($role);

        $this->token = $this->user->createToken('Personal Access Token')->plainTextToken;

    }

    public function test_create_role_successfully(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->postJson('/api/roles', [
            'name' => 'test-role',
            'display_name' => 'Test Role',
        ]);
        $response->assertStatus(200);
    }


    public function test_throws_401_unauthenticated(): void
    {
        $response = $this->postJson('/api/roles', [
            'name' => 'test-role',
            'display_name' => 'Test Role',
        ]);
        $response->assertStatus(401);
    }

    public function test_throws_422_required_fields(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->postJson('/api/roles');

        $response->assertStatus(422);
    }

    public function test_update_role_successfully(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->patchJson('/api/roles/1', [
            'name' => 'admin',
            'display_name' => 'System Admin',
        ]);

        $response->assertStatus(200);
        $this->assertSame('System Admin', $response->json('data.display_name'));
    }


    public function test_delete_role_successfully(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->deleteJson('/api/roles/1');

        $response->assertStatus(200);
        $this->assertDatabaseMissing('roles', [
            'name' => 'admin',
        ]);
    }


}
