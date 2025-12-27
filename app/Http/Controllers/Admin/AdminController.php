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
use App\Mail\UserApproved;
use App\Mail\UserRejected;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard()
    {
    $stats = [
        'total_users' => User::where('role', 'user')->whereNotNull('email_verified_at')->count(),
        'pending_users' => User::where('role', 'user')
                            ->where('status', 'pending')
                            ->whereNotNull('email_verified_at')
                            ->count(),
        'active_users' => User::where('role', 'user')->where('status', 'verified')->count(),
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
public function approveUser(Request $request, $id)
{
    $user = User::where('role', 'user')->findOrFail($id);

    if (!in_array($user->status, ['pending', 'rejected'])) {
        return back()->with('error', 'User cannot be approved at this time.');
    }

    $user->update(['status' => 'verified']);

    // Get optional reason
    $reason = $request->input('reason');

    // Send email with reason (if any)
    Mail::to($user->email)->send(new UserApproved($user, $reason));

    UserActivityLog::create([
        'user_id' => $user->id,
        'admin_id' => auth()->id(),
        'action_type' => 'activated',
        'reason' => $reason ?: 'Admin approved user profile',
    ]);

    return back()->with('success', 'User approved and email sent successfully.');
}

public function rejectUser(Request $request, $id)
{
    $user = User::where('role', 'user')->findOrFail($id);

    if ($user->status !== 'pending') {
        return back()->with('error', 'User is not pending approval.');
    }

    // Get reason from admin (or use default)
    $reason = $request->input('reason', 'Your account was not approved by the admin.');

    $user->update(['status' => 'rejected']);

    // Send email with reason
    Mail::to($user->email)->send(new UserRejected($user, $reason));

    UserActivityLog::create([
        'user_id' => $user->id,
        'admin_id' => auth()->id(),
        'action_type' => 'rejected',
        'reason' => $reason,
    ]);

    return back()->with('success', 'User rejected and email sent.');
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

public function usersManagement(Request $request)
{
    $query = User::where('role', 'user');

    // ===== SEARCH =====
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('full_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('designation', 'like', "%{$search}%");
        });
    }

    // ===== FILTERS =====
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('email_verified')) {
        if ($request->email_verified === 'verified') {
            $query->whereNotNull('email_verified_at');
        } elseif ($request->email_verified === 'unverified') {
            $query->whereNull('email_verified_at');
        }
    }

    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    // ===== SORTING =====
    $sortField = $request->get('sort', 'created_at');
    $sortOrder = $request->get('order', 'desc');

    // Only allow safe columns to avoid SQL injection
    $allowedSorts = ['full_name', 'email', 'status', 'created_at'];
    if (!in_array($sortField, $allowedSorts)) {
        $sortField = 'created_at';
    }

    $query->orderBy($sortField, $sortOrder);

    // ===== STATS (filtered-aware or total — here we keep total for dashboard clarity) =====
    $stats = [
        'verified' => User::where('role', 'user')->where('status', 'verified')->count(),
        'pending_verified' => User::where('role', 'user')->where('status', 'pending')->whereNotNull('email_verified_at')->count(),
        'unverified' => User::where('role', 'user')->where('status', 'pending')->whereNull('email_verified_at')->count(),
        'rejected' => User::where('role', 'user')->where('status', 'rejected')->count(),
        'suspended' => User::where('role', 'user')->where('status', 'suspended')->count(),
    ];

    // ===== PAGINATION =====
    $users = $query->withCount(['jobPostings', 'productOffers'])
                   ->latest()
                   ->paginate(10)
                   ->appends($request->except('page')); // Preserve filters/sort in pagination links

    return view('admin.users-management', compact('users', 'stats', 'request'));
}
}