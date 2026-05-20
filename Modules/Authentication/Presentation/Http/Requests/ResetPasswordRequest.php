<?php

namespace Modules\Authentication\Presentation\Http\Requests;

use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class ResetPasswordRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => __('validation.attributes.token.required'),
            'email.required' => __('validation.attributes.email.required'),
            'email.exists' => __('validation.attributes.email.exists'),
            'password.required' => __('validation.attributes.password.required'),
            'password.min' => __('validation.attributes.password.min'),
            'password.max' => __('validation.attributes.password.max'),
        ];
    }
}
