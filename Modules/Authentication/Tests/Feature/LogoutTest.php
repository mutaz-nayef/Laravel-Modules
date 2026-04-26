<?php

namespace Modules\Authentication\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_logout_success(): void
    {
        $user = UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'message' => 'UserModel Logged Out',
                'status' => 200,
            ]);
    }

    public function test_logout_not_authenticated(): void
    {
        $user = UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.' ',
        ])->postJson('api/logout');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

}
