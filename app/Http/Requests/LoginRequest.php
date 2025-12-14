<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email is required.',
            'email.exists' => 'Email does not exist.',
            'password.required' => 'Password is required.',
        ];
    }
}
