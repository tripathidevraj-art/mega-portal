<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\ProductOffer;
use App\Models\UserActivityLog;
use App\Models\AdminActionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'active_users' => User::where('role', 'user')->where('status', 'active')->count(),
            'suspended_users' => User::where('role', 'user')->where('status', 'suspended')->count(),
            'pending_jobs' => JobPosting::pending()->count(),
            'pending_offers' => ProductOffer::pending()->count(),
            'expired_jobs' => JobPosting::expired()->count(),
            'expired_offers' => ProductOffer::expired()->count(),
            'total_jobs' => JobPosting::count(),
            'total_offers' => ProductOffer::count(),
        ];

        $recentActivities = UserActivityLog::with(['user', 'admin'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentActivities'));
    }

    public function approvalQueue()
    {
        $pendingJobs = JobPosting::pending()->with('user')->latest()->get();
        $pendingOffers = ProductOffer::pending()->with('user')->latest()->get();

        return view('admin.approval-queue', compact('pendingJobs', 'pendingOffers'));
    }

    public function expiredContent()
    {
        $expiredJobs = JobPosting::expired()->with(['user', 'approvedBy'])->latest()->get();
        $expiredOffers = ProductOffer::expired()->with(['user', 'approvedBy'])->latest()->get();

        return view('admin.expired-content', compact('expiredJobs', 'expiredOffers'));
    }

    public function userLogs(Request $request)
    {
        $query = UserActivityLog::with(['user', 'admin']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->paginate(20);

        return view('admin.user-logs', compact('logs'));
    }

    public function userAnalytics(Request $request)
    {
        $query = User::where('role', 'user')
            ->withCount([
                'jobPostings as total_jobs',
                'jobPostings as approved_jobs_count' => function($q) {
                    $q->where('status', 'approved');
                },
                'productOffers as total_offers',
                'productOffers as approved_offers_count' => function($q) {
                    $q->where('status', 'approved');
                }
            ]);

        // Filter by activity level
        if ($request->filled('activity')) {
            if ($request->activity === 'most_active') {
                $query->orderBy('total_jobs', 'desc')->orderBy('total_offers', 'desc');
            } elseif ($request->activity === 'least_active') {
                $query->orderBy('total_jobs', 'asc')->orderBy('total_offers', 'asc');
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(20);

        return view('admin.user-analytics', compact('users'));
    }

    public function adminLogs(Request $request)
    {
        $query = AdminActionLog::with(['admin', 'targetUser']);

        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        if ($request->filled('target_type')) {
            $query->where('target_type', $request->target_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->latest()->paginate(20);

        return view('admin.admin-logs', compact('logs'));
    }

    public function usersManagement()
    {
        $users = User::where('role', 'user')->withCount(['jobPostings', 'productOffers'])->latest()->paginate(20);
        return view('admin.users-management', compact('users'));
    }
}