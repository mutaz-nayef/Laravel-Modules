<?php

namespace Modules\Authentication\Infrastructure\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Authentication\Infrastructure\Database\factories\UserFactory;
use Modules\Authorization\Infrastructure\Models\PermissionModel;
use Modules\Authorization\Infrastructure\Models\RoleModel;
use Modules\Authorization\Infrastructure\Models\UserPermissionPivot;

#[UseFactory(UserFactory::class)]
class UserModel extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;


    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(RoleModel::class, 'user_roles', 'user_id', 'role_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            PermissionModel::class,
            'user_permissions',
            'user_id',
            'permission_id'
        )
            ->using(UserPermissionPivot::class)
            ->withPivot('conditions');  // ← load conditions from pivot
    }
}
