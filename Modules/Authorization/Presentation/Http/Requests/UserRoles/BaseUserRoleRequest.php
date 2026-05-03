<?php

namespace Modules\Authorization\Presentation\Http\Requests\UserRoles;

use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class BaseUserRoleRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'roles' => ['required', 'array', 'min:1', 'exists:roles,name'],
        ];
    }

}

