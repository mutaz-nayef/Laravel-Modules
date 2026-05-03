<?php

namespace Modules\Authorization\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Authentication\Domain\ValueObjects\UserId;
use Modules\Authorization\Domain\Entities\UserRole;
use Modules\Authorization\Domain\Repositories\UserRoleRepositoryInterface;

class UserRoleRepository implements UserRoleRepositoryInterface
{
    public function create(UserId $userId, string $roleId): UserRole
    {
        $id = DB::table('user_has_roles')->insertGetId([
            'user_id' => $userId,
            'role_id' => $roleId,
            'created_at' => now(),
        ]);
        return new UserRole(
            id: $id,
            userId: $userId,
            roleId: $roleId,
            createdAt: new \DateTimeImmutable()
        );
    }

    public function save(UserRole $userRole): void
    {
        // TODO: Implement save() method.
    }

    public function exists(UserId $userId, string $roleId): bool
    {
        return DB::table('user_has_roles')
            ->where('user_id', $userId->value)
            ->where('role_id', $roleId)
            ->exists();
    }

    public function delete(UserId $userId, string $roleId): bool
    {
        return DB::table('user_has_roles')
                ->where('user_id', $userId->value)
                ->where('role_id', $roleId)
                ->delete() > 0;
    }

    public function getRoleNames(UserId $userId): array
    {
        return DB::table('user_has_roles')
            ->join('roles', 'user_has_roles.role_id', '=', 'roles.id')
            ->where('user_has_roles.user_id', $userId->value)
            ->pluck('name')
            ->toArray();
    }
}
