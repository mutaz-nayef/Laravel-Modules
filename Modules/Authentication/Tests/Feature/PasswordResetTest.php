<?php

namespace Modules\Authentication\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Modules\Authentication\Domain\Events\PasswordResetRequested;
use Modules\Authentication\Domain\Events\PasswordResetSuccessfully;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_password_reset_link(): void
    {
        Event::fake();
        UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);
        $response = $this->postJson('api/forget-password', [
            'email' => 'test@example.com',
        ]);
        $response->assertStatus(200)
            ->assertJson([
                "success" => true,
                "data" => "",
                "errors" => null,
                "message" => "We have emailed your password reset link.",
                "status" => 200
            ]);
        Event::assertDispatched(PasswordResetRequested::class, function ($event) {
            return $event->email === 'test@example.com';
        });
    }

    public function test_password_reset_link_click_twice(): void
    {
        UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);
        $response = $this->postJson('api/forget-password', [
            'email' => 'test@example.com',
        ]);
        $response = $this->postJson('api/forget-password', [
            'email' => 'test@example.com',
        ]);
        $response->assertStatus(422)
            ->assertJson([
                "success" => false,
                "data" => null,
                "errors" => "Please wait before retrying.",
                "message" => "",
                "status" => 422
            ]);
    }

    public function test_password_reset_successfully(): void
    {
        Event::fake();
        $user = UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);
        $token = Password::createToken($user);

        $response = $this->postJson('api/reset-password', [
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => $token,
        ]);
        $response->assertStatus(200)
            ->assertJson([
                "success" => true,
                "data" => "",
                "errors" => null,
                "message" => "Your password has been reset.",
                "status" => 200
            ]);
        Event::assertDispatched(PasswordResetSuccessfully::class, function ($event) {
            return $event->email === 'test@example.com';
        });
    }

    public function test_password_reset_invalid_token(): void
    {
        $user = UserModel::factory()->create([
            'name' => 'Test UserModel',
            'email' => 'test@example.com',
        ]);

        $response = $this->postJson('api/reset-password', [
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'token' => 'invalid_token_123043',
        ]);
        $response->assertStatus(422)
            ->assertJson([
                "success" => false,
                "data" => "",
                "errors" => "This password reset token is invalid.",
                "message" => "",
                "status" => 422
            ]);
    }

}
