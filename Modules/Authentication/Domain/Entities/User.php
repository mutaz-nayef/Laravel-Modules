<?php

namespace Modules\Authentication\Domain\Entities;

use DateTimeImmutable;
use Modules\Authentication\Domain\Events\BaseEvent;
use Modules\Authentication\Domain\Events\LoginAttemptedOutsideAllowedTime;
use Modules\Authentication\Domain\Events\UserLoggedIn;
use Modules\Authentication\Domain\Exceptions\EmailNotVerifiedException;
use Modules\Authentication\Domain\Exceptions\InvalidCredentialsException;
use Modules\Authentication\Domain\Exceptions\LoginNotAllowedThisTimeException;
use Modules\Authentication\Domain\Exceptions\UserNotActiveException;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authorization\Domain\Entities\Permission;
use Modules\Authorization\Domain\Entities\Role;
use Modules\Shared\Domain\ValueObjects\UserId;

final class User
{
    private array $events = [];

    /**
     * @param  Role[]  $roles
     * @param  Permission[]  $permissions
     */
    public function __construct(
        private readonly string $name,
        private readonly Email $email,
        private readonly HashedPassword $password,
        private readonly bool $isActive,
        private readonly bool $isEmailVerified,
        private ?array $roles = [],
        private ?array $permissions = [],
        private readonly ?UserId $id = null,

    ) {
    }

    /**
     * @throws UserNotActiveException
     * @throws EmailNotVerifiedException
     * @throws InvalidCredentialsException
     * @throws LoginNotAllowedThisTimeException
     * @throws \DateMalformedStringException
     */
    public function login(string $plain, ?DateTimeImmutable $now = null): void
    {
        if (!$this->verifyLoginTime($now)) {
            // fix
            $this->record(new LoginAttemptedOutsideAllowedTime($this->email()));

            throw new LoginNotAllowedThisTimeException('You are not allowed to login now', 403);
        }
        if (!$this->isActive) {
            throw new UserNotActiveException('Account is not active, Please support contact', 403);
        }

        if (!$this->password->verify($plain)) {
            throw new InvalidCredentialsException('Invalid credentials. Please try again.', 401);
        }
        $this->record(new UserLoggedIn(($this->email)));

    }

    /**
     * @throws \DateMalformedStringException
     */
    public function verifyLoginTime(?DateTimeImmutable $now): bool
    {

        $now = $now?->format('H') ?? new DateTimeImmutable('now', new \DateTimeZone('Asia/Jerusalem'))->format('H');

        $start = (int) new  \DateTime('8:00')->format('H');
        $end = (int) new  \DateTime('20:00')->format('H');
        return ($now >= $start && $now <= $end);
    }

    private function record(BaseEvent $event): void
    {
        $this->events[] = $event;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function isEmailVerified(): bool
    {
        return $this->isEmailVerified;
    }

    /**
     * @return Role[]|null
     */
    public function roles(): ?array
    {
        return $this->roles;
    }

    public function id(): ?UserId
    {
        return $this->id;
    }

    /**
     * Check if user has any of listed roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return array_any($roles, $this->roles);
    }

    /**
     * Check if user has all listed roles
     */
    public function hasAllRoles(array $roles): bool
    {
        return array_all($roles, $this->roles);
    }

    /**
     * Get all roles for user
     */
    public function getRoleNames()
    {
        $this->loadMissing('roles');
        return $this->roles->pluck('auth_code');
    }

    // ---Getters---

    public function pullEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }

    public function password(): HashedPassword
    {
        return $this->password;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function can(string $permission): bool
    {
        if (in_array($permission, $this->permissions)) {
            return true;
        }

        if (!empty($this->roles)) {
            foreach ($this->roles as $role) {
                if ($role->hasPermissionTo($permission)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function hasPermissionTo(Permission $permission): bool
    {
        return in_array($permission, $this->permissions());
    }

    /**
     * @return Permission[]|null
     */
    public function permissions(): ?array
    {
        return $this->permissions;
    }

    public function hasDirectPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions());
    }

    public function getAllPermissions(): array
    {
        $permissions = $this->permissions();

        foreach ($this->roles as $role) {
            foreach ($role->permissions() as $permission) {
                $permissions[] = $permission;
            }
        }
        return array_values(array_unique($permissions));
    }

    public function syncRoles(array $roles): void
    {
        // Remove old ones not in new set
        foreach ($this->roles as $existing) {
            if (!in_array($existing, $roles, true)) {
                $this->revokeRole(($existing));
            }
        }

        // Add new ones not already present
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                $this->assignRole($role);
            }
        }
    }

    /**
     * Remove role from user
     */
    public function revokeRole(Role $role): void
    {
        $this->roles = array_filter(
            $this->roles, fn($roleName) => $roleName->name() !== $role->name());
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * Check if user has role.
     */
    public function hasRole(Role $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function assignRole(Role $role): void
    {
        if ($this->hasRole($role)) {
            return;
        }
        $this->roles[] = $role;
    }

    public function syncPermissions(array $permissions): void
    {
        // Remove old ones not in new set
        foreach ($this->permissions as $existing) {
            if (!in_array($existing, $permissions, true)) {
                $this->revokePermissionTo(($existing));
            }
        }

        // Add new ones not already present
        foreach ($permissions as $permission) {
            if (!$this->hasPermissionTo($permission)) {
                $this->givePermissionTo($permission);
            }
        }
    }

    public function revokePermissionTo(Permission $permission): void
    {
        $this->permissions = array_filter(
            $this->permissions,
            fn($perm) => $perm->name() !== $permission->name()
        );
    }

    public function givePermissionTo(Permission $permission): void
    {
        if ($this->hasPermissionTo($permission)) {
            return;
        }

        $this->permissions[] = $permission;
    }

}
