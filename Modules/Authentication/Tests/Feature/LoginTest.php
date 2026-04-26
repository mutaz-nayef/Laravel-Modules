<?php

namespace Modules\Authentication\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Modules\Authentication\Domain\Events\LoginAttemptedOutsideAllowedTime;
use Modules\Authentication\Domain\Events\UserLoggedIn;
use Modules\Authentication\Domain\Services\LoginPolicy;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private LoginPolicy $loginPolicy;

    public function test_user_login_valid_credentials_allowed_time(): void
    {
        Event::fake();

        $this->travelTo(now()->setHour(13)->setMinute(0)->setSecond(0));

        UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);
        $response = $this->postJson('api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                "user" => [
                    "id",
                    "name",
                    "email",
                ],
                "token" => [
                    "token",
                ],
                'timeNow',
            ],
            'errors',
            'message',
            'status',
        ]);

        $this->travelBack();

        Event::assertDispatched(UserLoggedIn::class, function ($event) {
            return $event->user->email->value === 'test@example.com';
        });
    }

    public function test_user_login_invalid_selected_email(): void
    {
        UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);
        $response = $this->postJson('api/login', [
            'email' => 'test@example.comff',
            'password' => 'password',
        ]);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'email' => ['The selected email is invalid.'],
                ],
                'message' => "",
                'status' => 422
            ]);
    }

    public function test_user_login_invalid_credentials(): void
    {
        if ($this->loginPolicy->canLogin(now())) {

            UserModel::factory()->create([
                'name' => 'Test UserModel',
                'email' => 'test@example.com',
            ]);
            $response = $this->postJson('api/login', [
                'email' => 'test@example.com',
                'password' => 'passworddasdas',
            ]);
            $response->assertStatus(401)
                ->assertJson([
                    'success' => false,
                    'data' => null,
                    'errors' => 'Invalid Credentials',
                    'message' => "",
                    'status' => 401,
                ]);
        } else {

            $this->test_user_login_not_allowed_time();
        }
    }

    // Freeze time to 09:00 (allowed)

    public function test_user_login_not_allowed_time(): void
    {
        Event::fake();

        $this->travelTo(now()->setHour(1)->setMinute(0));

        UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);
        $response = $this->postJson('api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        $response->assertStatus(401);
        $response->assertJson([
            "success" => false,
            "data" => "",
            "errors" => "You are not allowed to log in at this time.",
            "message" => "",
            "status" => 401
        ]);
        $this->travelBack();
        Event::assertDispatched(LoginAttemptedOutsideAllowedTime::class, function ($event) {
            return $event->email === 'test@example.com';
        });

    }

    public function test_user_login_invalid_email(): void
    {
        UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);
        $response = $this->postJson('api/login', [
            'email' => 'fdsfds',
            'password' => 'password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'email' => ['The email field must be a valid email address.'],
                ],
                'message' => "",
                'status' => 422
            ]);
    }

    public function test_user_login_empty_email(): void
    {
        UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);
        $response = $this->postJson('api/login', [
            'email' => '',
            'password' => 'password',
        ]);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'email' => ['The email field is required.'],
                ],
                'message' => "",
                'status' => 422
            ]);
    }

    public function test_user_login_empty_password(): void
    {
        UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);
        $response = $this->postJson('api/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'password' => ['The password field is required.'],
                ],
                'message' => "",
                'status' => 422
            ]);
    }

    public function test_user_login_empty_email_and_password(): void
    {
        UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);
        $response = $this->postJson('api/login', [
            'email' => '',
            'password' => '',
        ]);
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'data' => null,
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ],
                'message' => "",
                'status' => 422
            ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loginPolicy = new LoginPolicy();
    }


}
