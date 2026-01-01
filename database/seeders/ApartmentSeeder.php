<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Apartment;
use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Log;

class ApartmentSeeder extends Seeder
{
    public function run(): void
    {
        $host = User::where('role', 'Host')->first();
        $locations = Location::limit(4)->get();

        if (!$host || $locations->count() < 4) {
            return;
        }

        Apartment::create([
            'user_id' => $host->id,
            'location_id' => $locations[0]->id,
            'title' => 'Luxury Flat - Damascus Mezzeh',
            'description' => 'Brand new flat with great view.',
            'rooms' => 2,
            'style' => 'Modern',
            'area' => 120,
            'rent_price' => 1000,
            'rent_period' => 'monthly',
            'is_active' => true,
            'amenities' => ['wifi' => true, 'parking' => true]
        ]);
        Apartment::create([
            'user_id' => $host->id,
            'location_id' => $locations[1]->id,
            'title' => 'Cozy Apartment - Al-Malki',
            'description' => 'Perfect for solo travelers.',
            'rooms' => 2,
            'style' => 'classic',
            'area' => 120,
            'rent_price' => 1000,
            'rent_period' => 'monthly',
            'is_active' => true,
            'amenities' => ['kitchen' => true, 'tv' => true]
        ]);
        Apartment::create([
            'user_id' => $host->id,
            'location_id' => $locations[2]->id,
            'title' => 'Aleppo Central Stay',
            'description' => 'Fully furnished and quiet.',
            'rooms' => 2,
            'style' => 'classic',
            'area' => 120,
            'rent_price' => 1000,
            'rent_period' => 'monthly',
            'is_active' => true,
            'amenities' => ['ac' => true, 'heater' => true]
        ]);
        Apartment::create([
            'user_id' => $host->id,
            'location_id' => $locations[3]->id,
            'title' => 'Latakia Seaside Home',
            'description' => 'Enjoy the sunset view.',
            'rooms' => 2,
            'area' => 120,
            "style" => 'Modern',
            'rent_price' => 1000,
            'rent_period' => 'monthly',
            'is_active' => false,
            'amenities' => ['balcony' => true, 'pool' => false]
        ]);
    }
}
