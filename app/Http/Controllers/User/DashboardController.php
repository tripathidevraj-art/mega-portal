<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // app/Http/Controllers/User/DashboardController.php
public function index()
{
    $stats = [
        'jobs_count' => JobPosting::where('user_id', auth()->id())->count(),
        'offers_count' => ProductOffer::where('user_id', auth()->id())->count(),
        'applications_count' => JobApplication::where('user_id', auth()->id())->count(),
        'profile_complete' => 80, // calculate based on your fields
    ];

    $recentJobs = JobPosting::where('user_id', auth()->id())
        ->withCount('applications')
        ->latest()
        ->limit(5)
        ->get();

    $recentOffers = ProductOffer::where('user_id', auth()->id())
        ->latest()
        ->limit(5)
        ->get();

    return view('user.dashboard', compact('stats', 'recentJobs', 'recentOffers'));
}
}
