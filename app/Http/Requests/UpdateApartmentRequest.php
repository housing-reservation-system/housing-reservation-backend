<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateApartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => "sometimes|string|max:255",
            'description' => "sometimes|string|max:1000",
            'rooms' => "sometimes|integer|min:1",
            'area' => "sometimes|numeric|min:1",
            'rent_price' => "sometimes|numeric|min:0",
            'rent_period' => "sometimes|in:daily,weekly,monthly,yearly",
            'style' => "sometimes|in:modern,classic",
            'amenities' => "nullable|array",
            'amenities.*' => "string|max:255",
            'latitude' => "sometimes|numeric",
            'longitude' => "sometimes|numeric",
            'province' => "sometimes|string|max:255",
            'city' => "sometimes|string|max:255",
            'street' => "sometimes|string|max:255",
        ];
    }
}
