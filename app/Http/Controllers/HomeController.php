<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $userId = auth()->id();

        // Cached leaderboard (top 100)
        $leaderboard = Cache::remember('referral_leaderboard', 600, function () {
            return DB::table('referrals')
                ->select('referrer_id', DB::raw('SUM(points_awarded) as total_points'))
                ->groupBy('referrer_id')
                ->orderByDesc('total_points')
                ->limit(100)
                ->get();
        });

        // User rank (computed live for MVP)
        $yourRank = DB::table('referrals')
            ->select(DB::raw('COUNT(DISTINCT referrer_id) + 1 as rank'))
            ->where('points_awarded', '>', function ($q) use ($userId) {
                $q->select(DB::raw('SUM(points_awarded)'))
                    ->from('referrals')
                    ->where('referrer_id', $userId);
            })
            ->value('rank');

        return view('home', compact('leaderboard', 'yourRank'));
    }
}
