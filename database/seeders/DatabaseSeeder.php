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
User::firstOrCreate(
    ['email' => 'superadmin@example.com'],
    [
        'full_name' => 'Super Admin',
        'phone' => '+91989878675',
        'date_of_birth' => '1985-01-01',
        'gender' => 'male',
        'country' => 'India',
        'current_address' => 'Super Admin Address',
        'occupation' => 'System Administrator',
        'company' => 'Job Portal',
        'skills' => ['PHP', 'Laravel', 'Management'],
        'password' => Hash::make('password123'),
        'role' => 'superadmin',
        'status' => 'verified',
        'email_verified_at' => now(),
    ]
);

        // Create Admin User
        User::create([
            'full_name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '+1234567890',
            'date_of_birth' => '1985-01-15',
            'gender' => 'male',
            'country' => 'USA',
            'current_address' => '123 Admin Street, Admin City',
            'occupation' => 'System Administrator',
            'company' => 'Job Portal Inc.',
            'skills' => ['PHP', 'Laravel', 'MySQL', 'JavaScript'],
            'civil_id' => 'A12345678',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'verified',
            'email_verified_at' => now(),
        ]);

        // Create Regular Users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'full_name' => 'User ' . $i,
                'email' => 'user' . $i . '@example.com',
                'phone' => '+123456789' . $i,
                'date_of_birth' => Carbon::now()->subYears(rand(20, 40))->subMonths(rand(1, 11))->subDays(rand(1, 28)),
                'gender' => rand(0, 1) ? 'male' : 'female',
                'country' => ['India', 'USA', 'UK', 'UAE', 'Saudi Arabia'][rand(0, 4)],
                'current_address' => 'Address ' . $i . ', City ' . $i,
                'occupation' => ['Developer', 'Manager', 'Designer', 'Analyst', 'Engineer'][rand(0, 4)],
                'company' => 'Company ' . $i,
                'skills' => ['PHP', 'JavaScript', 'Python', 'Java', 'React'][rand(0, 4)],
                'civil_id' => 'C' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 'verified',
                'email_verified_at' => now(),
            ]);
        }

        // Create pending user (not verified)
        User::create([
            'full_name' => 'Pending User',
            'email' => 'pending@example.com',
            'phone' => '+9876543210',
            'date_of_birth' => '1995-05-20',
            'gender' => 'female',
            'country' => 'India',
            'current_address' => '456 Pending Street, Pending City',
            'occupation' => 'Developer',
            'company' => 'Tech Corp',
            'skills' => ['JavaScript', 'React'],
            'password' => Hash::make('password123'),
            'role' => 'user',
            'status' => 'pending',
        ]);

        // Create suspended user
        User::create([
            'full_name' => 'Suspended User',
            'email' => 'suspended@example.com',
            'phone' => '+1122334455',
            'date_of_birth' => '1990-08-10',
            'gender' => 'male',
            'country' => 'UAE',
            'current_address' => '789 Suspended Street, Suspended City',
            'occupation' => 'Manager',
            'company' => 'Suspended Corp',
            'skills' => ['Management', 'Leadership'],
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