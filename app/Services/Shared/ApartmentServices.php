<?php

namespace App\Services\Shared;

use App\Http\Requests\StoreApartmentRequest;
use App\Models\Apartment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
class ApartmentServices{
public function createApartment(StoreApartmentRequest $request): Apartment
{
$user=Auth::user();
$apartment =Apartment::create([
'user_id'=>$user->id,
'location_id'=>$request->location_id,
'title'=>$request->title,
'description'=>$request->description,
'amenities'=>$request->amenities
]);
return $apartment;
}
public function getApartmentList(): Collection
{

return Apartment::with('media')->select('id','title','price','price_per_month','price_per_year')->get();
}
public function searchApartments(Request $request): Collection
{
$query=Apartment::query();
if($request->has('province')){
$query->whereHas('location',
function ($q) use ($request){ $q->where('province',$request->input('province'));
});
}
if($request->has('city')){
    $query->whereHas('location',function($q) use ($request){
        $q->where('city',$request->input('city'));
    });
}
if($request->has('monthly_price_less_than')){
    $query->where('price_per_month','<=',$request->input('monthly_price_less_than'));
}
if($request->has('monthly_Price_more_than')){
    $query->where('price_per_month','<=',$request->input('monthly_price_more_than'));
}
if($request->has('yearly_price_less_than')){
    $query->where('price_per_year','<=',$request->input('yearly_price_less_than'));
}
if($request->has('yearly_Price_more_than')){
    $query->where('price_per_year','<=',$request->input('yearly_price_more_than'));
}
if($request->has('rooms')){
    $query->where('number_of_rooms',$request->input('rooms'));
}
return $query->select('id','title','price_per_month','price_per_year','number_of_rooms')->latest()->get();
}
public function getApartmentDetails(Apartment $apartment): Apartment
{
    return $apartment;
}

}

