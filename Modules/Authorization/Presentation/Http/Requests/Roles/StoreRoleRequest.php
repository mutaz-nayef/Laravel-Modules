<?php

namespace Modules\Authorization\Presentation\Http\Requests\Roles;

use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class StoreRoleRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:20', 'unique:roles,name'],
            'display_name' => ['required', 'string', 'min:3', 'max:20'],
        ];
    }

}

