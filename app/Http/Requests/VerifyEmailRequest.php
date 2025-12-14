<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerifyEmailRequest extends FormRequest
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
            'code' => 'required|string|size:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.exists' => 'Email not found.',
            'code.required' => 'Verification code is required.',
            'code.size' => 'Verification code must be 6 digits.',
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
