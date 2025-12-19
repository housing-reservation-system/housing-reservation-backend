<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    use ApiResponse;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "first_name" => 'required|string|max:255',
            "last_name" => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'date_of_birth' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'phone.required' => 'Phone number is required.',
            'phone.unique' => 'Phone number is already taken.',
            'password.required' => 'Password is required.',
            'role.required' => 'Role is required.',
            'role.in' => 'Role must be one of the following: Tenant, Host.',
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
