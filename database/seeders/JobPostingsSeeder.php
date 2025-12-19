<?php

namespace Database\Seeders;

use App\Models\JobPosting;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class JobPostingsSeeder extends Seeder
{
    public function run(): void
    {
        $jobTypes = ['full_time', 'part_time', 'contract', 'remote', 'hybrid'];
        $industries = ['IT', 'Healthcare', 'Finance', 'Education', 'Retail', 'Manufacturing'];
        
        // Get all users
        $users = \App\Models\User::where('role', 'user')->get();
        
        foreach ($users as $user) {
            // Create 2-5 jobs per user
            $jobCount = rand(2, 5);
            
            for ($i = 1; $i <= $jobCount; $i++) {
                $status = ['pending', 'approved', 'rejected', 'expired'][rand(0, 3)];
                $deadline = Carbon::now()->addDays(rand(-10, 30)); // Some expired, some future
                
                $job = JobPosting::create([
                    'user_id' => $user->id,
                    'job_title' => ucfirst($industries[rand(0, 5)]) . ' ' . ['Developer', 'Manager', 'Analyst', 'Specialist', 'Engineer'][rand(0, 4)],
                    'job_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                    'industry' => $industries[rand(0, 5)],
                    'job_type' => $jobTypes[rand(0, 4)],
                    'work_location' => ['Remote', 'Office', 'Hybrid'][rand(0, 2)] . ' - ' . ['New York', 'London', 'Dubai', 'Mumbai', 'Singapore'][rand(0, 4)],
                    'salary_range' => '$' . rand(30, 100) . ',000 - $' . rand(101, 200) . ',000',
                    'app_deadline' => $deadline,
                    'status' => $status,
                    'approved_by_admin_id' => $status === 'approved' || $status === 'rejected' ? 1 : null,
                    'approved_at' => $status === 'approved' || $status === 'rejected' ? Carbon::now()->subDays(rand(1, 10)) : null,
                ]);
                
                // If job is approved but deadline passed, mark as expired
                if ($job->status === 'approved' && $job->app_deadline < Carbon::now()) {
                    $job->update(['status' => 'expired']);
                }
            }
        }
        
        $this->command->info('Job postings seeded successfully!');
    }
}