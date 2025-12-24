<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'full_name' => 'Super Admin',
                'phone_country_code' => '+91',
                'phone' => '989878675',
                'date_of_birth' => '1985-01-01',
                'gender' => 'male',
                'country' => 'India',
                'state' => 'Gujarat',
                'city' => 'Ahmedabad',
                'zip_code' => '380001',
                'current_address' => 'Super Admin Address',
                'communication_address' => 'Super Admin Address',
                'designation' => 'System Administrator',
                'company_name' => 'Job Portal',
                'industry_experience' => '10+',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
                'status' => 'verified',
                'email_verified_at' => now(),
            ]
        );

        // Admin User
        User::create([
            'full_name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone_country_code' => '+1',
            'phone' => '234567890',
            'date_of_birth' => '1985-01-15',
            'gender' => 'male',
            'country' => 'USA',
            'state' => 'California',
            'city' => 'San Francisco',
            'zip_code' => '94107',
            'current_address' => '123 Admin Street',
            'communication_address' => '123 Admin Street',
            'designation' => 'System Administrator',
            'company_name' => 'Job Portal Inc.',
            'industry_experience' => '7-10',
            'civil_id' => 'A12345678',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'verified',
            'email_verified_at' => now(),
        ]);

        // Regular Users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'full_name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'phone_country_code' => '+1',
                'phone' => '23456789' . $i,
                'date_of_birth' => Carbon::now()->subYears(rand(20, 40))->subMonths(rand(1, 11))->subDays(rand(1, 28)),
                'gender' => rand(0, 1) ? 'male' : 'female',
                'country' => ['India', 'USA', 'UK', 'UAE', 'Saudi Arabia'][rand(0, 4)],
                'state' => 'State ' . $i,
                'city' => 'City ' . $i,
                'zip_code' => '12345',
                'current_address' => 'Address ' . $i,
                'communication_address' => 'Address ' . $i,
                'designation' => ['Developer', 'Manager', 'Designer', 'Analyst', 'Engineer'][rand(0, 4)],
                'company_name' => 'Company ' . $i,
                'industry_experience' => ['0-3', '4-6', '7-10', '10+'][rand(0, 3)],
                'civil_id' => 'C' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 'verified',
                'email_verified_at' => now(),
            ]);
        }

        // Pending User
        User::create([
            'full_name' => 'Pending User',
            'email' => 'pending@example.com',
            'phone_country_code' => '+91',
            'phone' => '9876543210',
            'date_of_birth' => '1995-05-20',
            'gender' => 'female',
            'country' => 'India',
            'state' => 'Maharashtra',
            'city' => 'Mumbai',
            'zip_code' => '400001',
            'current_address' => '456 Pending Street',
            'communication_address' => '456 Pending Street',
            'designation' => 'Developer',
            'company_name' => 'Tech Corp',
            'industry_experience' => '4-6',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'status' => 'pending',
        ]);

        // Suspended User
        User::create([
            'full_name' => 'Suspended User',
            'email' => 'suspended@example.com',
            'phone_country_code' => '+971',
            'phone' => '1122334455',
            'date_of_birth' => '1990-08-10',
            'gender' => 'male',
            'country' => 'UAE',
            'state' => 'Dubai',
            'city' => 'Downtown',
            'zip_code' => '123456',
            'current_address' => '789 Suspended Street',
            'communication_address' => '789 Suspended Street',
            'designation' => 'Manager',
            'company_name' => 'Suspended Corp',
            'industry_experience' => '7-10',
            'civil_id' => 'S12345678',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'status' => 'suspended',
            'suspension_reason' => 'Violation of terms',
            'suspended_until' => Carbon::now()->addDays(30),
        ]);

        $this->call([
            JobPostingsSeeder::class,
            ProductOffersSeeder::class,
        ]);
    }
}