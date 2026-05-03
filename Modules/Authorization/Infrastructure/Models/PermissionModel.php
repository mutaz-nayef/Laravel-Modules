<?php

namespace Modules\Authorization\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Authentication\Infrastructure\Models\UserModel;

class PermissionModel extends Model
{

    protected $table = 'permissions';

    protected $fillable = ['name', 'group'];


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(RoleModel::class, 'role_permissions', 'permission_id', 'role_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(UserModel::class, 'user_permissions', 'permission_id', 'user_id');
    }
}
