<?php

namespace Modules\Authentication\Infrastructure\Repositories;

use Illuminate\Support\Collection;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Entities\User;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Modules\Authorization\Domain\ValueObjects\RoleId;
use Modules\Authorization\Infrastructure\Mappers\PermissionMapper;
use Modules\Authorization\Infrastructure\Mappers\RoleMapper;
use Modules\Shared\Domain\ValueObjects\UserId;

class EloquentUserRepository implements UserRepositoryInterface
{

    public function findById(UserId $id): ?User
    {
        $model = UserModel::find($id->value());

        return $model ? $this->toDomainEntity($model) : null;
    }

    public function toDomainEntity(UserModel|Collection $model): User|array
    {
        if ($model instanceof Collection) {
            return $model
                ->map(fn(UserModel $user) => $this->toDomainEntity($user))
                ->all();
        }
        return new User(
            id: new UserId($model->id),
            name: $model->name,
            email: new Email($model->email),
            password: new HashedPassword($model->password),
            isActive: (bool) $model->is_active,
            isEmailVerified: $model->email_verified_at !== null,
            roles: RoleMapper::toDomainEntity($model->roles),
            permissions: PermissionMapper::toDomainEntity($model->refresh()->permissions),
        );
    }

    public function findByEmail(Email $email): ?User
    {
        $model = UserModel::where('email', $email->value())->first();

        return $model ? $this->toDomainEntity($model) : null;
    }

    public function save(User $user): ?User
    {
        $model = UserModel::updateOrCreate(
            [
                'id' => $user->id()?->value(),
            ],
            [
                'name' => $user->name(),
                'email' => $user->email()->value(),
                'password' => $user->password()->hash()
            ]
        );
        $model = $this->syncRoles($model, $user->roles());

        return $model ? $this->toDomainEntity($model->refresh()) : null;
    }

    private function syncRoles(UserModel $model, array $roles): UserModel
    {
        $model->roles()->sync(array_map(fn($r) => $r->id()->value(), $roles));

        return $model;
    }

    public function saveRoles(User $user): ?User
    {
        $model = UserModel::findOrFail($user->id()->value());

        $model->roles()->sync(array_map(fn($r) => $r->id()->value(), $user->roles()));

        return $this->toDomainEntity($model->refresh());
    }

    public function savePermissions(User $user): ?User
    {
        $model = UserModel::findOrFail($user->id()->value());

//        if (!empty($user->permissions())) {
        $model->permissions()->sync(array_map(fn($p) => $p->id()->value(), $user->permissions()));
//        }
        return $model ? $this->toDomainEntity($model->refresh()) : null;
    }

    public function getAuthUsers(): ?array
    {
        return PersonalAccessToken::query()
            ->with('tokenable')
            ->where('expires_at', '>', now())
            ->get()
            ->pluck('tokenable')
            ->unique('id')
            ->map(fn($user) => $this->toDomainEntity($user))
            ->values()
            ->all();
    }

    public function findByRole(string|RoleId $role): ?array
    {
        if ($role instanceof RoleId) {
            return UserModel::whereHas('roles', fn($q) => $q->where('id', '=', $role->value()))
                ->get()
                ->map(fn($user) => $this->toDomainEntity($user))
                ->values()
                ->all();
        }
        return UserModel::whereHas('roles', fn($q) => $q->where('name', $role))
            ->get()
            ->map(fn($user) => $this->toDomainEntity($user))
            ->values()
            ->all();
    }

    public function getAdmins(): ?array
    {
        return UserModel::query()
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->where('roles.name', '=', 'admin')
            ->select('users.*')
            ->get()
            ->map(fn($user) => $this->toDomainEntity($user))
            ->values()
            ->all();
    }

}
