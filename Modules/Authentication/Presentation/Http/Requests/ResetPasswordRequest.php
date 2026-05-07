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
}
