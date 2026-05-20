<?php

namespace Modules\Authentication\Presentation\Http\Requests;

use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class ForgetPasswordRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => __('validation.attributes.email.required'),
            'email.exists' => __('validation.attributes.email.exists'),
        ];
    }

}
