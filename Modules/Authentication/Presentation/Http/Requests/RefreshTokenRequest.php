<?php

namespace Modules\Authentication\Presentation\Http\Requests;

use Modules\Shared\Presentation\Http\Requests\BaseFormRequest;

class RefreshTokenRequest extends BaseFormRequest
{

    public function rules(): array
    {
        return [
            'refresh_token' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'refresh_token.required' => __('validation.attributes.refresh_token.required'),
        ];
    }
}
