<?php

namespace Modules\Authentication\Domain\Entities;

use Modules\Authentication\Domain\Events\BaseEvent;
use Modules\Authentication\Domain\Events\LoginAttemptedOutsideAllowedTime;
use Modules\Authentication\Domain\Events\UserLoggedIn;
use Modules\Authentication\Domain\Events\UserRegistered;
use Modules\Authentication\Domain\Exceptions\EmailNotVerifiedException;
use Modules\Authentication\Domain\Exceptions\InvalidCredentialsException;
use Modules\Authentication\Domain\Exceptions\LoginNotAllowedThisTimeException;
use Modules\Authentication\Domain\Exceptions\UserNotActiveException;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Shared\Domain\ValueObjects\UserId;

final class User
{
    private array $events = [];
//    /**
//     * @param Role[] $roles
//     */
    public function __construct(
        private readonly ?UserId $id = null,
        private readonly string $name,
        private readonly Email $email,
        private readonly HashedPassword $password,
        private readonly bool $isActive,
        private readonly bool $isEmailVerified,
//        private ?array $roles = null,
    )
    {
    }

    public static function register(string $name, Email $email, HashedPassword $password): self
    {

        $user = new self(
            null,
            $name,
            $email,
            $password,
            true,
            false
        );
        $user->record(new UserRegistered($email));

        return $user;
    }


    private function record(BaseEvent $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @throws UserNotActiveException
     * @throws EmailNotVerifiedException
     * @throws InvalidCredentialsException
     * @throws LoginNotAllowedThisTimeException
     */
    public function login(string $plain): void
    {
        if (!$this->verifyLoginTime()) {
            // fix
            $this->record(new LoginAttemptedOutsideAllowedTime($this->email()));

            throw new LoginNotAllowedThisTimeException('You are not allowed to login now', 403);
        }
        if (!$this->isActive) {
            throw new UserNotActiveException('Account is not active, Please support contact', 403);
        }
        if (!$this->isEmailVerified) {
            throw new EmailNotVerifiedException('Email is not verified. Please check your inbox for the verification link.',
                403);
        }
        if (!$this->password->verify($plain)) {
            throw new InvalidCredentialsException('Invalid credentials. Please try again.', 401);
        }
        $this->record(new UserLoggedIn(($this->email)));

    }

    protected function verifyLoginTime(): bool
    {
        $now = new \DateTime();
        $start = new \DateTime('08:00');
        $end = new \DateTime('14:00');

        return ($now >= $start && $now <= $end);
    }

    public function email(): Email
    {
        return $this->email;
    }

    // ---Getters---

    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }

    public function id(): ?UserId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function password(): HashedPassword
    {
        return $this->password;
    }

//    public function role(): ?array {return $this->roles;}

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function isEmailVerified(): bool
    {
        return $this->isEmailVerified;
    }
}
