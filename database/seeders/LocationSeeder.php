<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::create([
            'province' => 'Damascus',
            'city' => 'Mezzeh',
            'street' => 'Western Villas',
            'point' => DB::raw("ST_GeomFromText('POINT(36.2570 33.5042)')")
        ]);

        Location::create([
            'province' => 'Damascus',
            'city' => 'Al-Malki',
            'street' => 'Central',
            'point' => DB::raw("ST_GeomFromText('POINT(36.2872 33.5137)')")
        ]);

        Location::create([
            'province' => 'Aleppo',
            'city' => 'Jamilieh',
            'street' => 'Downtown',
            'point' => DB::raw("ST_GeomFromText('POINT(36.2084 37.1245)')")
        ]);

        Location::create([
            'province' => 'Latakia',
            'city' => 'Al-Ziraa',
            'street' => 'Near Beach',
            'point' => DB::raw("ST_GeomFromText('POINT(35.5398 35.7909)')")
        ]);
    }
}
