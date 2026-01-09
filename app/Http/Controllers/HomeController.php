<?php

namespace App\Http\Controllers;

// ✅ Explicitly import the correct base controller
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\News;

class HomeController extends Controller
{
    public function __construct()
    {
        // ✅ Now this will work
        $this->middleware('auth');
    }

    public function index()
    {
        $latestNews = News::where('is_published', true)
            ->where(function ($query) {
                $query->where('published_at', '<=', now())
                      ->orWhereNull('published_at');
            })
            ->latest()
            ->take(10)
            ->get();

        $topReferrers = User::whereHas('referralsGiven')
            ->withSum('referralsGiven as total_points', 'points_awarded')
            ->orderByDesc('total_points')
            ->limit(10)
            ->get();

        return view('home', compact('latestNews', 'topReferrers'));
    }
}