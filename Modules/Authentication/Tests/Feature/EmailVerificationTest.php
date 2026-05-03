<?php

namespace Modules\Authentication\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_db_connection_is_active(): void
    {
        $dbName = DB::connection()->getDatabaseName();

        $this->assertNotNull($dbName);
    }

    public function test_email_verification_send(): void
    {
        $user = UserModel::factory()->unverified()->create();

        $token = $user->createToken('Personal Access Token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('api/email/verification-notification');

        $response->assertStatus(200)
            ->assertJson([
                "success" => true,
                "data" => "",
                "errors" => null,
                "message" => "Verification link sent!",
                "status" => 200
            ]);
    }

    public function test_email_verification_email_already_verified(): void
    {
        $user = UserModel::factory()->create();
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('api/email/verification-notification');

        $response->assertStatus(409)
            ->assertJson([
                "success" => false,
                "data" => "",
                "errors" => "Email already verified.",
                "message" => "",
                "status" => 409
            ]);
    }

    public function test_email_verified_successfully(): void
    {
        $user = UserModel::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        $response = $this->actingAs($user)->get($verificationUrl);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertStatus(200)
            ->assertJson([
                "success" => true,
                "data" => "",
                "errors" => null,
                "message" => "Email has been verified",
                "status" => 200
            ]);
    }

    public function test_email_verified_invalid_token(): void
    {
        $user = UserModel::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        $response = $this->actingAs($user)->get('http://localhost:8000/api/verify-email/2/567159d622ffbb50b11b0efd307be358624a26ee?expires=1775735522&signature=bb5ee926ac079aaaffe8b4c433d03391df7ece6ac60cd9ee7a3d503486c6623c');

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
        $response->assertStatus(403)
            ->assertJson([
                "success" => false,
                "data" => "",
                "errors" => "This is unauthorized action!",
                "message" => "",
                "status" => 403
            ]);
    }

}

