<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'phone' => '',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('STARRS@2025'),
            'first_name' => 'Admin',
            'last_name' => 'System',
            'date_of_birth' => Carbon::parse('1985-01-01'),
            'email_verified_at' => Carbon::parse('2025-12-01'),
            'role' => 'Admin',
            'status' => "Approved",
        ]);

        User::create([
            'phone' => '099854491',
            'email' => 'host@gmail.com',
            'password' => Hash::make('STARRS@2025'),
            'first_name' => 'Anas',
            'last_name' => 'Almbark',
            'date_of_birth' => Carbon::parse('1990-05-15'),
            'email_verified_at' => Carbon::parse('2025-12-01'),
            'role' => 'Host',
            'status' => "Approved",
        ]);

        User::create([
            'phone' => '0998888888',
            'email' => 'tenant@gmail.com',
            'password' => Hash::make('STARRS@2025'),
            'first_name' => 'Shimaa',
            'last_name' => 'Othman',
            'date_of_birth' => Carbon::parse('1995-10-20'),
            'email_verified_at' => Carbon::parse('2025-12-01'),
            'role' => 'Tenant',
            'status' => "Approved",
        ]);
    }
}
