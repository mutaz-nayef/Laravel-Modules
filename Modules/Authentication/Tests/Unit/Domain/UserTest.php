<?php

namespace Modules\Authentication\Tests\Unit\Domain;

use Modules\Authentication\Domain\Entities\User;
use Modules\Authentication\Domain\Exceptions\EmailNotVerifiedException;
use Modules\Authentication\Domain\Exceptions\InvalidCredentialsException;
use Modules\Authentication\Domain\Exceptions\LoginNotAllowedThisTimeException;
use Modules\Authentication\Domain\Exceptions\UserNotActiveException;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Shared\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @throws \DateMalformedStringException
     * @throws LoginNotAllowedThisTimeException
     * @throws EmailNotVerifiedException
     * @throws UserNotActiveException
     * @throws InvalidCredentialsException
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = new User(
            id: new UserId(1),
            name: 'test',
            email: new Email('test@example.com'),
            password: HashedPassword::fromPlain('secret123'),
            isActive: true,
            isEmailVerified: true,
        );
        // Should not throw
        $user->login('secret123');
        $this->assertTrue(true);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $this->expectException(UserNotActiveException::class);

        $user = $this->makeUser(['isActive' => false]);
        $user->login('password');
    }

    private function makeUser(array $overrides = []): User
    {
        return new User(
            id: new UserId($overrides['id'] ?? 1),
            name: $overrides['name'] ?? 'test',
            email: new Email($overrides['email'] ?? 'test@example.com'),
            password: HashedPassword::fromPlain('password'),
            isActive: $overrides['isActive'] ?? true,
            isEmailVerified: $overrides['isEmailVerified'] ?? true,
        );
    }

    public function test_unverified_user_cannot_login(): void
    {
        $this->expectException(EmailNotVerifiedException::class);

        $user = $this->makeUser(['isEmailVerified' => false]);
        $user->login('password');
    }

    public function test_not_allowed_time_user_cannot_login(): void
    {
        $this->expectException(LoginNotAllowedThisTimeException::class);

        $user = $this->makeUser();
        $user->login('password', new \DateTimeImmutable('20:00:00'));

    }

    public function test_wrong_password_throws_invalid_credentials(): void
    {
        $this->expectException(InvalidCredentialsException::class);

        $user = new User(
            id: new UserId(1),
            name: 'test',
            email: new Email('test@example.com'),
            password: HashedPassword::fromPlain('correct-password'),
            isActive: true,
            isEmailVerified: true,
        );

        $user->login('wrong-password');
    }
}
