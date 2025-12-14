<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRule;


class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'current_password' => 'required|string',
            'password' => [
                'confirmed',
                'required',
                'string',
                PasswordRule::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ];
    }
}
