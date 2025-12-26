<?php

namespace App\Services;

use App\Models\Apartment;
use Exception;
use Illuminate\Http\Request;

class ApartmentTenantService
{

    public function index()
    {
        return Apartment::where('is_active', true)
            ->get(['id', 'title', 'rent_price', 'rent_period']);
    }

    public function show(Apartment $apartment)
    {
        if (!$apartment->is_active) {
            throw new Exception('Apartment is not available');
        }

        $apartment->load(['location']);
        return $apartment->fresh();
    }
    public function filter(Request $request)
{
    $query = Apartment::where('is_active', true);


    if ($request->filled('province')) {
        $query->whereHas('location', function($q) use ($request) {
            $q->where('province', 'LIKE', '%' . $request->province . '%');
        });
    }

    if ($request->filled('city')) {
        $query->whereHas('location', function($q) use ($request) {
            $q->where('city', 'LIKE', '%' . $request->city . '%');
        });
    }


    if ($request->filled('min_price')) {
        $query->where('rent_price', '>=', $request->min_price);
    }


    if ($request->filled('max_price')) {
        $query->where('rent_price', '<=', $request->max_price);
    }
    if ($request->filled('rent_period')){
$query->where('rent_period',$request->rent_period);
    }


    if ($request->filled('amenities')) {
        $amenities = is_array($request->amenities) ? $request->amenities : [$request->amenities];
        $query->where(function($q) use ($amenities) {
            foreach ($amenities as $amenity) {
                $q->orWhereJsonContains('amenities', $amenity);
            }
        });
    }


    return $query->with('location')->get();
}
}


