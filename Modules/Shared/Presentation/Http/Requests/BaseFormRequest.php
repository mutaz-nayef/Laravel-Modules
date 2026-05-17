<?php

namespace Modules\Shared\Presentation\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Shared\ApiResponses;

abstract class BaseFormRequest extends FormRequest
{
    use ApiResponses;

    public function authorize(): bool
    {
        return true;
    }

    abstract public function rules(): array;


    public function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(
            self::error($validator->errors(), 422)
        );
    }
}

