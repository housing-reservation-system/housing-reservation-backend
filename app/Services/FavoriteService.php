<?php

namespace App\Services;

use App\Models\Apartment;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class FavoriteService
{
public function toggleFavorite(User $user,$apartmentId){
    return DB::transaction(function () use($user,$apartmentId) {
$favorite=Favorite::where('user_id',$user->id)->where('apartment_id',$apartmentId)->first();
if($favorite){
    $favorite->delete();
    return['action'=>'removed'];
}
Favorite::create([
    'user_id'=>$user->id,
    'apartment_id'=>$apartmentId
]);
return['action'=>'added'];
    });
}
public function isFavorited(User $user,Apartment $apartment){
    return Favorite::where('user_id',$user->id)->where('apartment_id',$apartment->id)->exists();
}
}
