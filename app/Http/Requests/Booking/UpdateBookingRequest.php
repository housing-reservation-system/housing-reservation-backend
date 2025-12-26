<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => [
                'sometimes',
                'date',
                'after_or_equal:today',
            ],
            'duration' => 'sometimes|integer|min:1',
            'payment_method_id' => 'sometimes|exists:payment_methods,id',
        ];
    }
}
