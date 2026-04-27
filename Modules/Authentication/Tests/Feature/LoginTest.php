<?php

namespace Modules\Authentication\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;


    public function test_user_can_login_and_receive_token_with_permissions()
    {
//    $this->seed(RoleAndPermissionSeeder::class);
        //        $role = RoleModel::where('name', 'admin')->first();
//        $user = UserModel::factory()->create(['password' => bcrypt('password123')]);
//        $user->roles()->attach($role);

        $user = UserModel::factory()->create(['password' => bcrypt('password')]);
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user' => ['id', 'name', 'email', 'role'],
                    'permissions' => [],
                    'token' => ['access_token', 'token_type',],
                ],
                'errors',
                'message',
                'status'
            ])->assertJsonPath('data.token.token_type', 'Bearer');
        $this->assertNotEmpty($response->json('data.token.access_token'));
    }

    public function test_user_cannot_login_not_allowed_time(): void
    {
//        Event::fake();

        $this->travelTo(now()->setHour(20)->setMinute(0)->setSecond(0));
        $user = UserModel::factory()->create(['password' => bcrypt('password')]);

        $response = $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(403);

        $this->travelBack();

//        Event::assertDispatched(LoginAttemptedOutsideAllowedTime::class, function ($event) {
//            return $event->email === 'test@example.com';
//        });
    }

    public function test_returns_403_for_inactive_user(): void
    {
        UserModel::factory()->inactive()->create([
            'email' => 'inactive@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->postJson('/api/login', [
            'email' => 'inactive@test.com',
            'password' => 'password',
        ])->assertStatus(403);
    }

    public function test_returns_401_for_wrong_password(): void
    {
        UserModel::factory()->create(['email' => 'test@test.com', 'password' => bcrypt('correct')]);

        $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'wrong-password',
        ])->assertStatus(401);
    }

    public function test_returns_422_for_missing_fields(): void
    {
        $this->postJson('/api/login', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }


}
