<?php
// File: database/seeders/BookingSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Apartment;
use App\Models\PaymentMethod;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = User::where('role', 'Tenant')->first();
        $apartment1 = Apartment::first();
        $apartment2 = Apartment::skip(1)->first();
        $payment1 = PaymentMethod::first();
        $payment2 = PaymentMethod::skip(1)->first();

        if (!$tenant || !$apartment1 || !$apartment2 || !$payment1 || !$payment2) {
            return;
        }

        Booking::create([
            'user_id' => $tenant->id, 'apartment_id' => $apartment1->id,
            'start_date' => Carbon::now()->subDays(5), 'end_date' => Carbon::now()->subDays(2), 
            'total_price' => 452,
            'payment_method_id' => $payment1->id,
            'status' => 'Approved',
        ]);

        Booking::create([
            'user_id' => $tenant->id, 'apartment_id' => $apartment2->id,
            'start_date' => Carbon::now()->addMonths(1), 'end_date' => Carbon::now()->addMonths(1)->addDays(5),
            'total_price' => 231,
            'payment_method_id' => $payment2->id,
            'status' => 'Pending',
        ]);
    }
}