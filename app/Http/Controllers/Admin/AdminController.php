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

        // Recent user activities (actions done TO users)
        $userActivities = UserActivityLog::with(['user', 'admin'])
            ->latest()
            ->take(10)
            ->get();

        // Recent admin actions (actions done BY admins)
        $adminActions = AdminActionLog::with(['targetUser', 'admin'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'userActivities', 'adminActions'));
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

    public function approvalQueue(Request $request)
    {
        $queryJobs = JobPosting::where('status', 'pending')
            ->where('app_deadline', '>=', now()->toDateString());

        // Apply filters for jobs
        if ($request->filled('job_search')) {
            $queryJobs->where(function($q) use ($request) {
                $q->where('job_title', 'like', '%' . $request->job_search . '%')
                ->orWhere('industry', 'like', '%' . $request->job_search . '%');
            });
        }
        if ($request->filled('job_type')) {
            $queryJobs->where('job_type', $request->job_type);
        }
        if ($request->filled('job_deadline_from')) {
            $queryJobs->where('app_deadline', '>=', $request->job_deadline_from);
        }
        if ($request->filled('job_deadline_to')) {
            $queryJobs->where('app_deadline', '<=', $request->job_deadline_to);
        }

        // Sorting
        $jobSort = $request->get('job_sort', 'created_at_desc');
        switch($jobSort) {
            case 'created_at_asc': $queryJobs->orderBy('created_at', 'asc'); break;
            case 'deadline_asc': $queryJobs->orderBy('app_deadline', 'asc'); break;
            case 'title_asc': $queryJobs->orderBy('job_title', 'asc'); break;
            default: $queryJobs->orderBy('created_at', 'desc');
        }

        $pendingJobs = $queryJobs->get();

        // Similarly for Offers...
        $queryOffers = ProductOffer::where('status', 'pending')
            ->where('expiry_date', '>=', now()->toDateString());

        if ($request->filled('offer_search')) {
            $queryOffers->where(function($q) use ($request) {
                $q->where('product_name', 'like', '%' . $request->offer_search . '%')
                ->orWhere('category', 'like', '%' . $request->offer_search . '%');
            });
        }
        if ($request->filled('offer_category')) {
            $queryOffers->where('category', $request->offer_category);
        }
        if ($request->filled('offer_min_price')) {
            $queryOffers->where('price', '>=', $request->offer_min_price);
        }
        if ($request->filled('offer_max_price')) {
            $queryOffers->where('price', '<=', $request->offer_max_price);
        }

        $offerSort = $request->get('offer_sort', 'created_at_desc');
        switch($offerSort) {
            case 'created_at_asc': $queryOffers->orderBy('created_at', 'asc'); break;
            case 'expiry_asc': $queryOffers->orderBy('expiry_date', 'asc'); break;
            case 'price_asc': $queryOffers->orderBy('price', 'asc'); break;
            default: $queryOffers->orderBy('created_at', 'desc');
        }

        $pendingOffers = $queryOffers->get();

        return view('admin.approval-queue', compact('pendingJobs', 'pendingOffers'));
    }

    public function expiredContent(Request $request)
    {
        // ===== EXPIRED JOBS =====
        $jobQuery = JobPosting::expired()->with(['user', 'approvedBy']);

        // Search
        if ($request->filled('job_search')) {
            $jobQuery->where(function($q) use ($request) {
                $q->where('job_title', 'like', "%{$request->job_search}%")
                ->orWhere('industry', 'like', "%{$request->job_search}%");
            });
        }

        // Job Type Filter
        if ($request->filled('job_type')) {
            $jobQuery->where('job_type', $request->job_type);
        }

        // Date Range (Deadline)
        if ($request->filled('job_deadline_from')) {
            $jobQuery->whereDate('app_deadline', '>=', $request->job_deadline_from);
        }
        if ($request->filled('job_deadline_to')) {
            $jobQuery->whereDate('app_deadline', '<=', $request->job_deadline_to);
        }

        // Sort
        $jobSort = $request->get('job_sort', 'created_at');
        $jobOrder = $request->get('job_order', 'desc');
        $allowedJobSorts = ['app_deadline', 'views', 'created_at', 'job_title'];
        if (!in_array($jobSort, $allowedJobSorts)) $jobSort = 'created_at';
        $jobQuery->orderBy($jobSort, $jobOrder);

        $expiredJobs = $jobQuery->get();

        // ===== EXPIRED OFFERS =====
        $offerQuery = ProductOffer::expired()->with(['user', 'approvedBy']);

        // Search
        if ($request->filled('offer_search')) {
            $offerQuery->where(function($q) use ($request) {
                $q->where('product_name', 'like', "%{$request->offer_search}%")
                ->orWhere('category', 'like', "%{$request->offer_search}%");
            });
        }

        // Discount Filter
        if ($request->filled('offer_discount')) {
            if ($request->offer_discount == 'has_discount') {
                $offerQuery->where('discount', '>', 0);
            } elseif ($request->offer_discount == 'no_discount') {
                $offerQuery->where('discount', 0);
            }
        }

        // Date Range (Expiry)
        if ($request->filled('offer_expiry_from')) {
            $offerQuery->whereDate('expiry_date', '>=', $request->offer_expiry_from);
        }
        if ($request->filled('offer_expiry_to')) {
            $offerQuery->whereDate('expiry_date', '<=', $request->offer_expiry_to);
        }

        // Sort
        $offerSort = $request->get('offer_sort', 'created_at');
        $offerOrder = $request->get('offer_order', 'desc');
        $allowedOfferSorts = ['expiry_date', 'price', 'views', 'created_at', 'product_name'];
        if (!in_array($offerSort, $allowedOfferSorts)) $offerSort = 'created_at';
        $offerQuery->orderBy($offerSort, $offerOrder);

        $expiredOffers = $offerQuery->get();

        return view('admin.expired-content', compact('expiredJobs', 'expiredOffers'));
    }

    public function userLogs(Request $request)
    {
        // ===== USERS TAB: Actions done TO users =====
        $userLogQuery = UserActivityLog::with(['user', 'admin']);

        if ($request->filled('user_search')) {
            $userLogQuery->whereHas('user', function($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->user_search}%")
                ->orWhere('email', 'like', "%{$request->user_search}%");
            });
        }
        if ($request->filled('user_admin_search')) {
            $userLogQuery->whereHas('admin', function($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->user_admin_search}%")
                ->orWhere('email', 'like', "%{$request->user_admin_search}%");
            });
        }
        if ($request->filled('user_action_type')) {
            $userLogQuery->where('action_type', $request->user_action_type);
        }
        if ($request->filled('user_date_from')) {
            $userLogQuery->whereDate('created_at', '>=', $request->user_date_from);
        }
        if ($request->filled('user_date_to')) {
            $userLogQuery->whereDate('created_at', '<=', $request->user_date_to);
        }

        $userLogs = $userLogQuery->latest()->paginate(50, ['*'], 'user_page')->appends($request->except('user_page'));

        // ===== ADMINS TAB: Actions done BY admins =====
        $adminLogQuery = AdminActionLog::with(['admin', 'targetUser']);

        if ($request->filled('admin_search')) {
            $adminLogQuery->whereHas('targetUser', function($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->admin_search}%")
                ->orWhere('email', 'like', "%{$request->admin_search}%");
            });
        }
        if ($request->filled('admin_admin_search')) {
            $adminLogQuery->whereHas('admin', function($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->admin_admin_search}%")
                ->orWhere('email', 'like', "%{$request->admin_admin_search}%");
            });
        }
        if ($request->filled('admin_action')) {
            $adminLogQuery->where('action', $request->admin_action);
        }
        if ($request->filled('admin_date_from')) {
            $adminLogQuery->whereDate('created_at', '>=', $request->admin_date_from);
        }
        if ($request->filled('admin_date_to')) {
            $adminLogQuery->whereDate('created_at', '<=', $request->admin_date_to);
        }

        $adminLogs = $adminLogQuery->latest()->paginate(50, ['*'], 'admin_page')->appends($request->except('admin_page'));

        return view('admin.user-logs', compact('userLogs', 'adminLogs'));
    }

    public function userAnalytics(Request $request)
    {
        $query = User::where('role', 'user')
            ->withCount([
                'jobPostings as total_jobs',
                'jobPostings as approved_jobs_count' => fn($q) => $q->where('status', 'approved'),
                'productOffers as total_offers',
                'productOffers as approved_offers_count' => fn($q) => $q->where('status', 'approved'),
            ]);

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $allowed = ['full_name', 'email', 'total_jobs', 'total_offers', 'created_at'];
        if (!in_array($sort, $allowed)) $sort = 'created_at';

        $users = $query->orderBy($sort, $order)->paginate(15)->appends($request->except('page'));

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