<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'email' => 'required|email:filter',
            'code' => 'required|string|size:6',
            'password' => 'required|min:6',
        ];
    }
    public function messages()
    {
        return [
            'code.required' => "The password reset code is missing.",
            'password.confirmed' => "Password confirmation does not match.",
        ];
    }
}
