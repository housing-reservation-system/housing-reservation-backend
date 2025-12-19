<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    
    protected $fillable = [
        'point',
        'province',
        'city',
        'street',
    ];

    public function apartment()
    {
        return $this->hasOne(Apartment::class);
    }
}
