<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResendVerificationCodeRequest extends FormRequest
{
    use ApiResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.exists' => 'Email not found.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->error(
            "Validation Error",
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $validator->errors()
        ));
    }
}
