<?php

namespace Modules\Authentication\Presentation\Http\Requests;

use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class LoginRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('validation.attributes.email.required'),
            'email.exists' => __('validation.attributes.email.exists'),
            'password.required' => __('validation.attributes.password.required'),
            'password.min' => __('validation.attributes.password.min'),
            'password.max' => __('validation.attributes.password.max'),
        ];
    }
}
