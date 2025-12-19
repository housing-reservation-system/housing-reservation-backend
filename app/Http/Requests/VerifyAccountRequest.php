<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_front' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'id_back' => 'required|image|mimes:jpeg,jpg,png|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'id_front.required' => 'Front side of ID is required',
            'id_front.image' => 'Front side must be an image',
            'id_front.mimes' => 'Front side must be jpeg, jpg, or png',
            'id_front.max' => 'Front side image must not exceed 5MB',
            'id_back.required' => 'Back side of ID is required',
            'id_back.image' => 'Back side must be an image',
            'id_back.mimes' => 'Back side must be jpeg, jpg, or png',
            'id_back.max' => 'Back side image must not exceed 5MB',
        ];
    }
}
