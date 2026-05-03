<?php

namespace Modules\Authorization\Presentation\Http\Requests\Permissions;

use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class StorePermissionRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3', 'max:20',
                'regex:/^[a-z]+:[a-z]+$/',
                'unique:permissions,name'
            ],
            'group' => [
                'required', 'string', 'min:3', 'max:20',
                'regex:/^[a-z]+$/'
            ],
        ];
    }

}

