<?php

use Illuminate\Support\Facades\Broadcast;
use Modules\Authentication\Infrastructure\Models\UserModel;


Broadcast::channel('roles.{roleId}', function (UserModel $user, $roleId) {
    return $user->roles()->where('id', '=', $roleId)->exists();
});

Broadcast::channel('admins', function (UserModel $user) {
    return $user->roles()->where('name', '=', 'admin')->exists();
});
