<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'user_id',
        'card_brand',
        'last_four_digits',
        'card_holder_name',
        'expiry_date',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
