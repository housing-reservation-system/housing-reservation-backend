<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|unique:users,phone,' . $userId,
            'date_of_birth' => 'sometimes|date|before:today',
            'gender' => 'sometimes|in:male,female',
            'photo' => 'sometimes|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.string' => 'First name must be a valid string',
            'last_name.string' => 'Last name must be a valid string',
            'phone.unique' => 'This phone number is already taken',
            'date_of_birth.date' => 'Please provide a valid date of birth',
            'date_of_birth.before' => 'Date of birth must be before today',
            'gender.in' => 'Gender must be either male or female',
            'photo.image' => 'Photo must be an image',
            'photo.mimes' => 'Photo must be jpeg, jpg, or png',
            'photo.max' => 'Photo must not exceed 5MB',
        ];
    }
}
