<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'apartment_id',
        'payment_method_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
        'is_modified',
        'cancellation_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
}
