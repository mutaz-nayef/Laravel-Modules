<?php

namespace Modules\Authorization\Presentation\Http\Requests\UserPermissions;

use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class BaseUserPermissionsRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'permissions' => ['required', 'array', 'min:1', 'exists:permissions,name'],
        ];
    }

}

