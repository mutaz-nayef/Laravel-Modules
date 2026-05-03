<?php

namespace Modules\Authorization\Presentation\Http\Requests\Roles;

use Illuminate\Validation\Rule;
use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class UpdateRoleRequest extends BaseFormRequest
{

    public function rules(): array
    {

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:20',
                Rule::unique('roles', 'name')
                    ->ignore($this->route('role')),
            ],
            'display_name' => ['required', 'string', 'min:3', 'max:20'],
        ];
    }

}

