<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = User::where('role', 'Tenant')->first();

        if (!$tenant) {
            return;
        }

        PaymentMethod::create([
            'user_id' => $tenant->id, 'method_type' => 'Credit_Card', 'card_brand' => 'VISA',
            'last_four_digits' => '1234', 'card_holder_name' => 'Anas Almbark',
            'expiry_date' => Carbon::parse('2028-10-01'), 'is_default' => true,
            'token' => Hash::make('card_visa_1234_'.time())
        ]);

        PaymentMethod::create([
            'user_id' => $tenant->id, 'method_type' => 'Credit_Card', 'card_brand' => 'MASTERCARD',
            'last_four_digits' => '9876', 'card_holder_name' => 'Anas Almbark',
            'expiry_date' => Carbon::parse('2026-05-01'), 'is_default' => false,
            'token' => Hash::make('card_master_9876_'.time())
        ]);
    }
}