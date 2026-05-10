<?php

namespace Modules\Authorization\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Modules\Authorization\Infrastructure\Database\Seeders\RolesAndPermissionsSeeder;
use Modules\Authorization\Infrastructure\Models\PermissionModel;
use Modules\Authorization\Infrastructure\Models\RoleModel;
use Tests\TestCase;

class PermissionTest extends TestCase
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

    public function test_create_permission_successfully(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->postJson('/api/permissions', [
            'name' => 'books:view',
            'group' => 'books',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('permissions', [
            'name' => 'books:view',
        ]);
    }


    public function test_throws_401_unauthenticated(): void
    {
        $response = $this->postJson('/api/permissions', [
            'name' => 'books:view',
            'group' => 'books',
        ]);
        $response->assertStatus(401);
    }

    public function test_throws_422_required_fields(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->postJson('/api/permissions');

        $response->assertStatus(422);
    }

    public function test_update_permission_successfully(): void
    {
        $permission = PermissionModel::create([
            'name' => 'books:view',
            'group' => 'books',
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->patchJson('/api/permissions/1', [
            'name' => 'books:updated',
            'group' => 'books',
        ]);

        $response->assertStatus(200);
        $this->assertSame('books:updated', $response->json('data.name'));
    }


    public function test_delete_permission_successfully(): void
    {
        $permission = PermissionModel::create([
            'name' => 'books:view',
            'group' => 'books',
        ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token,
        ])->deleteJson("/api/permissions/".$permission->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('permissions', [
            'id' => $permission->id,
        ]);
    }

}
