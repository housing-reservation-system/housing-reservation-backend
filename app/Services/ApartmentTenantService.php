<?php

namespace App\Services;

use App\Models\Apartment;
use Exception;

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
}


