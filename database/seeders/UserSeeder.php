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
            'username' => 'admin.test',
            'password' => Hash::make('password'),
            'first_name' => 'Admin',
            'last_name' => 'System',
            'date_of_birth' => Carbon::parse('1985-01-01'),
            'role' => 'Admin',
            'status' => "Approved",
        ]);

        User::create([
            'phone' => '0999654321',
            'username' => 'host.test',
            'password' => Hash::make('password'),
            'first_name' => 'Anas',
            'last_name' => 'Almbark',
            'date_of_birth' => Carbon::parse('1990-05-15'),
            'role' => 'Host',
            'status' => "Approved",
        ]);

        User::create([
            'phone' => '0998888888',
            'username' => 'tenant.test',
            'password' => Hash::make('password'),
            'first_name' => 'Shimaa',
            'last_name' => 'Othman',
            'date_of_birth' => Carbon::parse('1995-10-20'),
            'role' => 'Tenant',
            'status' => "Approved",
        ]);
    }
}
