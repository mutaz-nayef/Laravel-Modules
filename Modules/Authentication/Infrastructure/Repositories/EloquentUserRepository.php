<?php

namespace Modules\Authentication\Infrastructure\Repositories;

use Modules\Authentication\Domain\Contracts\UserRepositoryInterface;
use Modules\Authentication\Domain\Entities\User;
use Modules\Authentication\Domain\ValueObjects\Email;
use Modules\Authentication\Domain\ValueObjects\HashedPassword;
use Modules\Authentication\Infrastructure\Models\UserModel;
use Modules\Shared\Domain\ValueObjects\UserId;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(UserId $id): ?User
    {
        $model = UserModel::find($id->value());

        return $model ? $this->toDomainEntity($model) : null;
    }

    public function toDomainEntity(UserModel $model): User
    {
        return new User(
            id: new UserId($model->id),
            name: $model->name,
            email: new Email($model->email),
            password: new HashedPassword($model->password),
            isActive: $model->is_active ? true : false,
            isEmailVerified: $model->email_verified_at !== null,
        );
    }

    public function findByEmail(Email $email): ?User
    {
        $model = UserModel::where('email', $email->value())->first();

        return $model ? $this->toDomainEntity($model) : null;
    }

    public function save(User $user): ?User
    {
        $model = UserModel::create([
            'name' => $user->name(),
            'email' => $user->email()->value(),
            'password' => $user->password()->hash()
        ]);
        return $model ? $this->toDomainEntity($model->refresh()) : null;

    }

}
