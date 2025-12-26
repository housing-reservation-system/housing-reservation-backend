<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;

class ApartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => "required|string|max:255",
            'description' => "required|string|max:1000",
            'rooms' => "required|integer|min:1",
            'area' => "required|numeric|min:1",
            'rent_price' => "required|numeric|min:0",
            'rent_period' => "required|in:daily,weekly,monthly,yearly",
            'style' => "required|in:modern,classic",
            'amenities' => "nullable|array",
            'amenities.*' => "string|max:255",
            'latitude' => "required|numeric",
            'longitude' => "required|numeric",
            'province' => "required|string|max:255",
            'city' => "required|string|max:255",
            'street' => "required|string|max:255",
            'main_image' => ['required', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
            'images' => "nullable|array|max:10",
            'images.*' => "image|mimes:jpeg,png,jpg,svg|max:2048",
        ];
    }
}
