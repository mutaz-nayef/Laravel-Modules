<?php

namespace Modules\Authorization\Presentation\Http\Requests\Permissions;

use Illuminate\Validation\Rule;
use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class UpdatePermissionRequest extends BaseFormRequest
{

    public function rules(): array
    {

        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^[a-z]+:[a-z]+$/',
                Rule::unique('permissions', 'name')
                    ->ignore($this->route('permission'))
            ],
            'group' => [
                'required', 'string', 'min:3', 'max:20',
                'regex:/^[a-z]+$/'
            ],
        ];
    }

}

