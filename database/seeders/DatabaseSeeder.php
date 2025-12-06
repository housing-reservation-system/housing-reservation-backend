<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use App\Models\Apartment;
use App\Models\PaymentMethod;
use App\Models\Booking;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class, 
            LocationSeeder::class,
            ApartmentSeeder::class,
            PaymentMethodSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
