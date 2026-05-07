<?php

namespace Modules\Authorization\Infrastructure\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserPermissionPivot extends Pivot
{
    protected $table = 'user_permissions';

    protected $casts = [
        'conditions' => 'array',
    ];
}
