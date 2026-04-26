<?php

namespace Modules\Authentication\Presentation\Http\Requests;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Shared\ApiResponses;

class EmailVerificationRequest extends FormRequest
{
    use ApiResponses;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (!hash_equals((string) $this->user()->getKey(), (string) $this->route('id'))) {
            return false;
        }

        if (!hash_equals(sha1($this->user()->getEmailForVerification()), (string) $this->route('hash'))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Fulfill the email verification request.
     *
     * @return void
     */
    public function fulfill(): void
    {
        if (!$this->user()->hasVerifiedEmail()) {
            $this->user()->markEmailAsVerified();

            event(new Verified($this->user()));
        }
    }

    public function failedAuthorization()
    {
        throw new HttpResponseException(
            self::error('This is unauthorized action!', 403)
        );
    }

    public function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(
            self::error($validator->errors(), 422)
        );
    }
}
