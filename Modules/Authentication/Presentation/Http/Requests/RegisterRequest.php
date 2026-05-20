<?php

namespace Modules\Authentication\Presentation\Http\Requests;

use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class RegisterRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:20'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:20', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.attributes.name.required'),
            'name.min' => __('validation.attributes.name.min'),
            'name.max' => __('validation.attributes.name.max'),
            'email.required' => __('validation.attributes.email.required'),
            'email.exists' => __('validation.attributes.email.exists'),
            'email.unique' => __('validation.attributes.email.unique'),
            'password.required' => __('validation.attributes.password.required'),
            'password.min' => __('validation.attributes.password.min'),
            'password.max' => __('validation.attributes.password.max'),
        ];
    }

}
