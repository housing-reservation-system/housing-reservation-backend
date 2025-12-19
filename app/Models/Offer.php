<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'apartment_id',
        'user_id',
        'discount_percentage',
        'start_date',
        'end_date',
        'offer_details',
        'is_active',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
