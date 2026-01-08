<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class UpdateReferralLeaderboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-referral-leaderboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache referral leaderboard rankings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Top 100 leaderboard
        $leaderboard = DB::table('referrals')
            ->select('referrer_id', DB::raw('SUM(points_awarded) as total_points'))
            ->groupBy('referrer_id')
            ->orderByDesc('total_points')
            ->limit(100)
            ->get();

        Cache::put('referral_leaderboard', $leaderboard, now()->addMinutes(10));

        $this->info('Referral leaderboard cached successfully.');
    }
}
