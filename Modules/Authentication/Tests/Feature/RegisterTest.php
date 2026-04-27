<?php

namespace Modules\Authentication\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_successfully(): void
    {
        $this->seed();

//        Event::fake();

        $user = [
            'name' => 'John Doe',
            'email' => 'john@example.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/register', $user);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'errors',
                'message',
                'status'
            ]);
//        Event::assertDispatched(UserRegistered::class, function ($event) use ($user) {
//            return $event->user->email->value === $user['email'];
//        });
    }

    public function test_register_user_email_exist(): void
    {
        UserModel::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.test'
        ]);
        $user = [
            'name' => 'John Doe',
            'email' => 'john@example.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson('/api/register', $user);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ],
                'message' => '',
                'status' => 422
            ]);
    }

    public function test_register_user_password_not_confirmed(): void
    {
        $user = [
            'name' => 'John Doe',
            'email' => 'John@example.test',
            'password' => 'password',
            'password_confirmation' => 'passworddd',
        ];
        $response = $this->postJson('/api/register', $user);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'password' => ['The password field confirmation does not match.'],
                ],
                'message' => '',
                'status' => 422
            ]);
    }

    public function test_register_user_empty_all_field(): void
    {

        $response = $this->postJson('/api/register', []);
        $response->assertStatus(422);
    }

    public function test_register_user_short_password(): void
    {
        $user = [
            'name' => 'John Doe',
            'email' => 'John@example.test',
            'password' => 'fd',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson('/api/register', $user);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'password' => [
                        'The password field must be at least 8 characters.',
                        'The password field confirmation does not match.'
                    ],
                ],
                'message' => '',
                'status' => 422
            ]);
    }

    public function test_register_user_short_name(): void
    {
        $user = [
            'name' => 'j',
            'email' => 'John@example.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson('/api/register', $user);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'name' => [
                        'The name field must be at least 2 characters.',
                    ],
                ],
                'message' => '',
                'status' => 422
            ]);
    }

}
