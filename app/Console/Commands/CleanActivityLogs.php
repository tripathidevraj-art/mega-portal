<?php

namespace App\Console\Commands;

use App\Models\UserActivityLog;
use App\Models\AdminActionLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clean {--days=30 : Delete logs older than this many days}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Clean up old activity logs';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);
        
        $this->info("Cleaning logs older than {$days} days (before {$cutoffDate->format('Y-m-d')})...");
        
        try {
            // Delete old user activity logs
            $userLogsDeleted = UserActivityLog::where('created_at', '<', $cutoffDate)->delete();
            $this->info("Deleted {$userLogsDeleted} user activity logs.");
            
            // Delete old admin action logs
            $adminLogsDeleted = AdminActionLog::where('created_at', '<', $cutoffDate)->delete();
            $this->info("Deleted {$adminLogsDeleted} admin action logs.");
            
            $totalDeleted = $userLogsDeleted + $adminLogsDeleted;
            $this->info("Total logs deleted: {$totalDeleted}");
            
            Log::info("Cleaned {$totalDeleted} logs older than {$days} days.");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('Error cleaning logs: ' . $e->getMessage());
            Log::error('Error cleaning logs: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
}