<?php

namespace App\Http\Controllers;

// ✅ Explicitly import the correct base controller
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\News;
use App\Models\JobPosting;
use App\Models\ProductOffer;

class HomeController extends Controller
{
    public function __construct()
    {
        // ✅ Now this will work
        $this->middleware('auth');
    }

public function index()
{
    $weatherApiKey = env('OPENWEATHER_API_KEY');

    // 📰 Latest News
    $latestNews = News::where('is_published', true)
        ->where(function ($query) {
            $query->where('published_at', '<=', now())
                  ->orWhereNull('published_at');
        })
        ->latest()
        ->take(10)
        ->get();

    // 👑 Top Referrers
    $topReferrers = User::whereHas('referralsGiven')
        ->withCount('referralsGiven')
        ->withSum('referralsGiven as total_points', 'points_awarded')
        ->orderByDesc('total_points')
        ->limit(10)
        ->get();

    // 💼 Latest Jobs
    $latestJobs = JobPosting::active()->with('user')->latest()->take(5)->get();

    // 🎁 Latest Offers
    $latestOffers = ProductOffer::active()->with('user')->latest()->take(5)->get();

    // 👔 Top Job Posters
    $topJobPosters = User::withCount(['jobPostings as job_count' => function ($query) {
            $query->where('status', 'approved');
        }])
        ->having('job_count', '>', 0)
        ->orderByDesc('job_count')
        ->limit(3)
        ->get();

    // 🛍️ Top Offer Posters
    $topOfferPosters = User::withCount(['productOffers as offer_count' => function ($query) {
            $query->where('status', 'approved');
        }])
        ->having('offer_count', '>', 0)
        ->orderByDesc('offer_count')
        ->limit(3)
        ->get();

    // 👮 All Admins
    $admins = User::whereIn('role', ['admin', 'superadmin'])
        ->where('status', '!=', 'suspended') // optional: skip suspended admins
        ->select('id', 'full_name', 'email', 'profile_image', 'designation', 'company_name')
        ->get();

    return view('home', compact(
        'weatherApiKey',
        'latestNews',
        'topReferrers',
        'latestJobs',
        'latestOffers',
        'topJobPosters',
        'topOfferPosters',
        'admins'
    ));
}
}