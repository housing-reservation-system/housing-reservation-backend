<?php

namespace App\Http\Requests\Booking;

use App\Models\Apartment;
use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreBookingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'apartment_id' => 'required|exists:apartments,id',
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'duration' => 'required|integer|min:1',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ];
    }
}
