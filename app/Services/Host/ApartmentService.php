<?php

namespace App\Services\Host;

use App\Models\Apartment;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class ApartmentService
{
    public function getUserApartments(int $userId)
    {
        return Apartment::where('user_id', $userId)
            ->with(['location' => function ($q) {
                $q->selectRaw('*, ST_X(point) as longitude, ST_Y(point) as latitude');
            }])
            ->get();
    }

    public function createApartment(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            $location = Location::create([
                'point' => DB::raw("ST_GeomFromText('POINT({$data['longitude']} {$data['latitude']})')"),
                'province' => $data['province'],
                'city' => $data['city'],
                'street' => $data['street'],
            ]);

            $apartment = Apartment::create([
                'user_id' => $userId,
                'location_id' => $location->id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'rooms' => $data['rooms'],
                'area' => $data['area'],
                'rent_price' => $data['rent_price'],
                'rent_period' => $data['rent_period'],
                'amenities' => $data['amenities'] ?? [],
                'is_active' => true,
            ]);

            if (isset($data['main_image'])) {
                $apartment->addMedia($data['main_image'])
                    ->withCustomProperties(['main' => true])
                    ->toMediaCollection('apartments', 'cloudinary');
            }

            if (isset($data['images'])) {
                foreach ($data['images'] as $image) {
                    $apartment->addMedia($image)
                        ->toMediaCollection('apartments', 'cloudinary');
                }
            }

            $apartment->load(['location' => function ($q) {
                $q->selectRaw('*, ST_X(point) as longitude, ST_Y(point) as latitude');
            }]);

            return $apartment;
        });
    }

    public function updateApartment(Apartment $apartment, array $data)
    {
        return DB::transaction(function () use ($apartment, $data) {
            if (isset($data['latitude'], $data['longitude']) && $apartment->location) {
                $apartment->location->update([
                    'point' => DB::raw("ST_GeomFromText('POINT({$data['longitude']} {$data['latitude']})')"),
                    'province' => $data['province'],
                    'city' => $data['city'],
                    'street' => $data['street'],
                ]);
            }
            $apartment->update([
                'title' => $data['title'] ?? $apartment->title,
                'description' => $data['description'] ?? $apartment->description,
                'rooms' => $data['rooms'] ?? $apartment->rooms,
                'area' => $data['area'] ?? $apartment->area,
                'rent_price' => $data['rent_price'] ?? $apartment->rent_price,
                'rent_period' => $data['rent_period'] ?? $apartment->rent_period,
                'amenities' => $data['amenities'] ?? $apartment->amenities,
            ]);

            $apartment->load(['location' => function ($q) {
                $q->selectRaw('*, ST_X(point) as longitude, ST_Y(point) as latitude');
            }]);

            return $apartment;
        });
    }

    public function updateApartmentImages(Apartment $apartment, $mainImage = null, $images = null)
    {
        return DB::transaction(function () use ($apartment, $mainImage, $images) {
            if ($mainImage) {
                $apartment->clearMediaCollection('apartments');

                $apartment->addMedia($mainImage)
                    ->withCustomProperties(['main' => true])
                    ->toMediaCollection('apartments', 'cloudinary');
            }

            if ($images) {
                foreach ($images as $image) {
                    $apartment->addMedia($image)
                        ->toMediaCollection('apartments', 'cloudinary');
                }
            }

            $apartment->load(['location' => function ($q) {
                $q->selectRaw('*, ST_X(point) as longitude, ST_Y(point) as latitude');
            }]);

            return $apartment->fresh();
        });
    }

    public function deleteApartment(Apartment $apartment)
    {
        return DB::transaction(function () use ($apartment) {
            $location = $apartment->location;
            $apartment->delete();
            if ($location) {
                $location->delete();
            }
            return true;
        });
    }
}
