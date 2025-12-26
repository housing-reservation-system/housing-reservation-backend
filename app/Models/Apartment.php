<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Apartment extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;
    public array $translatable=[

        'title',
        'description',
        'rent_period',
        'style',
        'amenities',
       
    ];
        protected $fillable = [
        'user_id',
        'location_id',
        'title',
        'description',
        'rooms',
        'area',
        'rent_price',
        'rent_period',
        'style',
        'amenities',
        'is_active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'is_active' => 'boolean',
    ];

    public function getAmenitiesAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($value) ? $value : [];
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function host()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeWithCoordinates($query)
    {
        return $query->with(['location' => function ($q) {
            $q->selectRaw('*, ST_X(point) as longitude, ST_Y(point) as latitude');
        }]);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function favoredBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
