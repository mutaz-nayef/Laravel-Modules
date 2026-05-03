<?php

namespace Modules\Authorization\Presentation\Http\Requests\RolePermissions;

use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class BaseRolePermissionRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'permissions' => ['required', 'array', 'min:1', 'exists:permissions,name',],
        ];
    }

}

