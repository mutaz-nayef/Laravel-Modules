<?php

namespace Modules\Authentication\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Modules\Authentication\Domain\Events\UserRegistered;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user_with_listeners(): void
    {
        Event::fake();
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.test',
            'type' => 'student',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'errors',
                'message',
                'status'
            ]);
        Event::assertDispatched(UserRegistered::class, function ($event) {
            return $event->user->email->value === 'john@example.test';
        });
    }

    public function test_register_user_successfully(): void
    {
        $this->seed();

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
    }

    public function test_dispatches_user_registered_event()
    {
        Event::fake();
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
                'data' => [
                    'token',
                ],
                'errors',
                'message',
                'status'
            ]);
        Event::assertDispatched(UserRegistered::class, function ($event) {
            return $event->user->email->value === 'john@example.test';
        });
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

    public function test_register_user_empty_name(): void
    {
        $user = [
            'name' => '',
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
                    'name' => ['The name field is required.'],
                ],
                'message' => '',
                'status' => 422
            ]);
    }

    public function test_register_user_empty_email(): void
    {
        $user = [
            'name' => 'John Doe',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson('/api/register', $user);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'email' => ['The email field is required.'],
                ],
                'message' => '',
                'status' => 422
            ]);
    }

    public function test_register_user_empty_password(): void
    {
        $user = [
            'name' => 'John Doe',
            'email' => 'John@example.test',
            'password' => '',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson('/api/register', $user);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'password' => ['The password field is required.'],
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
        $user = [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ];
        $response = $this->postJson('/api/register', $user);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ],
            ]);
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

    public function test_register_user_short_name_and_short_password(): void
    {
        $user = [
            'name' => 'j',
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
                    'name' => ['The name field must be at least 2 characters.'],
                    'password' => [
                        'The password field must be at least 8 characters.',
                        'The password field confirmation does not match.'
                    ],
                ],
                'message' => '',
                'status' => 422
            ]);
    }

    public function test_register_user_email_exist_short_password_password_not_confirmed(): void
    {
        UserModel::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.test'
        ]);
        $user = [
            'name' => 'John Doe',
            'email' => 'john@example.test',
            'password' => 'pd',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson('/api/register', $user);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'email' => ['The email has already been taken.'],
                    'password' => [
                        'The password field must be at least 8 characters.',
                        'The password field confirmation does not match.'
                    ],
                ],
                'message' => '',
                'status' => 422
            ]);
    }

    public function test_register_user_email_exist_password_not_confirmed(): void
    {
        UserModel::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.test'
        ]);
        $user = [
            'name' => 'John Doe',
            'email' => 'john@example.test',
            'password' => 'password',
            'password_confirmation' => 'passwordddd',
        ];
        $response = $this->postJson('/api/register', $user);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'email' => ['The email has already been taken.'],
                    'password' => ['The password field confirmation does not match.'],
                ],
                'message' => '',
                'status' => 422
            ]);
    }
}
