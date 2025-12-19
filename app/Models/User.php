<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements JWTSubject, HasMedia
{
    use InteractsWithMedia, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'password',
        'date_of_birth',
        'role',
        'status',
        'gender',
        'email',
        'email_verification_code',
        'email_verified_at',
        'email_verification_code_expires_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verification_code_expires_at' => 'datetime',
            'role' => \App\Enums\UserRole::class,
            'status' => \App\Enums\StatusType::class,
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->useDisk('cloudinary');
        $this->addMediaCollection('id_back')->useDisk('cloudinary');
        $this->addMediaCollection('id_front')->useDisk('cloudinary');
    }

    public function getNameAttribute(): string
{
    return trim($this->first_name . ' ' . $this->last_name) ?: $this->email;
}

}
