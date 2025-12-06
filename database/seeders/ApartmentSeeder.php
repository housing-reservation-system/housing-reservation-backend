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
            'user_id' => $host->id, 'location_id' => $locations[0]->id,
            'title' => 'Luxury Flat - Damascus Mezzeh', 'description' => 'Brand new flat with great view.',
            'is_active' => true,
            'amenities' => json_encode(['wifi' => true, 'parking' => true])
        ]);
        Apartment::create([
            'user_id' => $host->id, 'location_id' => $locations[1]->id,
            'title' => 'Cozy Apartment - Al-Malki', 'description' => 'Perfect for solo travelers.',
            'is_active' => true,
            'amenities' => json_encode(['kitchen' => true, 'tv' => true])
        ]);
        Apartment::create([
            'user_id' => $host->id, 'location_id' => $locations[2]->id,
            'title' => 'Aleppo Central Stay', 'description' => 'Fully furnished and quiet.',
            'is_active' => true,
            'amenities' => json_encode(['ac' => true, 'heater' => true])
        ]);
        Apartment::create([
            'user_id' => $host->id, 'location_id' => $locations[3]->id,
            'title' => 'Latakia Seaside Home', 'description' => 'Enjoy the sunset view.',
            'is_active' => false,   
            'amenities' => json_encode(['balcony' => true, 'pool' => false])
        ]);
    }
}