<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    protected $fillable = ['user_id', 'token', 'platform', 'device_id', 'is_active', 'last_used_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
