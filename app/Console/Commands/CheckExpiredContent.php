<?php

namespace App\Console\Commands;

use App\Models\JobPosting;
use App\Models\ProductOffer;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckExpiredContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:check-expired';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Check and mark expired jobs and offers, and auto-activate suspended users';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting expired content check...');
        Log::info('Starting expired content check');
        
        try {
            DB::beginTransaction();
            
            // Mark expired jobs
            $expiredJobsCount = JobPosting::where('status', 'approved')
                ->where('app_deadline', '<', now()->toDateString())
                ->update(['status' => 'expired']);
            
            $this->info("Marked {$expiredJobsCount} jobs as expired.");
            Log::info("Marked {$expiredJobsCount} jobs as expired.");
            
            // Mark expired offers
            $expiredOffersCount = ProductOffer::where('status', 'approved')
                ->where('expiry_date', '<', now()->toDateString())
                ->update(['status' => 'expired']);
            
            $this->info("Marked {$expiredOffersCount} offers as expired.");
            Log::info("Marked {$expiredOffersCount} offers as expired.");
            
            // Check suspended users whose suspension period has ended
            $activatedUsersCount = User::where('status', 'suspended')
                ->where('suspended_until', '<=', now())
                ->update([
                    'status' => 'active',
                    'suspension_reason' => null,
                    'suspended_until' => null,
                ]);
            
            $this->info("Auto-activated {$activatedUsersCount} users.");
            Log::info("Auto-activated {$activatedUsersCount} users.");
            
            DB::commit();
            
            $this->info('Expired content check completed successfully.');
            Log::info('Expired content check completed successfully.');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->error('Error checking expired content: ' . $e->getMessage());
            Log::error('Error checking expired content: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
}