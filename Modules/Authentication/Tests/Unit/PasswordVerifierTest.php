<?php


namespace Modules\Authentication\Tests\Unit;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Authentication\Infrastructure\Services\Security\LaravelPasswordHasher;
use Modules\Authentication\Infrastructure\Services\Security\Md5PasswordHasher;
use Modules\Authentication\Infrastructure\Services\Security\PasswordHasherStrategy;
use Modules\Authentication\Infrastructure\Services\Security\PasswordVerifier;

class PasswordVerifierTest extends BaseTestCase
{
    private PasswordVerifier $passwordVerifier;
    private PasswordHasherStrategy $passwordHasher;

    public function setUp(): void
    {
         parent::setUp();
        $this->passwordHasher = new PasswordHasherStrategy(new LaravelPasswordHasher());
        $this->passwordVerifier = new PasswordVerifier($this->passwordHasher);
    }

    public function testVerifyCorrectPasswordWithLaravelHash(): void
    {
        $plain = 'password';

        $hashed = $this->passwordHasher->make($plain);

        $verified = $this->passwordVerifier->verify($plain, $hashed);

        $this->assertTrue($verified);
    }
    public function testVerifyCorrectPasswordWithMd5Hash(): void
    {
        $this->passwordHasher = new PasswordHasherStrategy(new Md5PasswordHasher());

        $plain = 'password';

        $hashed = $this->passwordHasher->make($plain);

        $verified = $this->passwordVerifier->verify($plain, $hashed);

        $this->assertTrue($verified);
    }

    public function testVerifyIncorrectPasswordWith(): void
    {
        $plain = 'password';

        $hashed = $this->passwordHasher->make('incorrect_password');

        $verified = $this->passwordVerifier->verify($plain, $hashed);

        $this->assertFalse($verified);
    }
}
