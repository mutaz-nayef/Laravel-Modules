<?php

namespace Modules\Authorization\Domain\Entities;

use Modules\Authorization\Domain\ValueObjects\RoleId;

final class Role
{
    /**
     * @param  Permission[]  $permissions
     */
    public function __construct(
        private readonly ?RoleId $id,
        private readonly string $name,
        private readonly string $display_name,
        private ?array $permissions = [],
    ) {
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

    public function name(): string
    {
        return $this->name;
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
        return $this?->permissions;
    }

    // Getters
    public function givePermissionTo(Permission $permission): void
    {
        if ($this->hasPermissionTo($permission)) {
            return;
        }
        $this->permissions[] = $permission;
    }

    /**
     * Retrieve the full Permission entity (including conditions).
     * Used by the PolicyEngine for ABAC evaluation.
     */
    public function findPermission(string $permissionName): ?Permission
    {
        foreach ($this->permissions as $permission) {
            if ($permission->name() === $permissionName) {
                return $permission;
            }
        }
        return null;
    }

    public function hasAnyPermission(array $permissions): bool
    {
        return array_any($this->permissions(), $permissions);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'display_name' => $this->displayName(),
            'permissions' => $this->permissions(),
        ];
    }

    public function id(): ?RoleId
    {
        return $this?->id;
    }

    public function displayName(): string
    {
        return $this->display_name;
    }
}
