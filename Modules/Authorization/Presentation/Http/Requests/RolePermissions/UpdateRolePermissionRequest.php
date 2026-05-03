<?php

namespace Modules\Authorization\Presentation\Http\Requests\RolePermissions;

use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class UpdateRolePermissionRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'add' => ['required', 'array'],
            'add.*' => ['required', 'string', 'exists:permissions,name'],
            'remove' => ['required', 'array'],
            'remove.*' => ['required', 'string', 'exists:permissions,name'],
        ];
    }

}

