<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Models\AdminActionLog;
use App\Http\Requests\SuspendUserRequest;
use App\Http\Requests\ActivateUserRequest;
use App\Mail\UserSuspended;
use App\Mail\UserReactivated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserManagementController extends Controller
{

// UserManagementController.php
public function suspendUser(SuspendUserRequest $request, $id)
{
    $user = User::where('role', 'user')->findOrFail($id);
    
    $suspendedUntil = $request->validated('suspended_until');
    $reason = $request->validated('reason');

    DB::transaction(function () use ($user, $suspendedUntil, $reason) {
        $user->update([
            'status' => 'suspended',
            'suspension_reason' => $reason,
            'suspended_until' => $suspendedUntil, // ✅ use directly
        ]);

        // ... logging code ...
    });

    // Calculate duration for email (optional)
    $durationDays = now()->diffInDays($suspendedUntil);
    Mail::to($user->email)->send(new UserSuspended($user, $reason, $durationDays));

return back()->with('success', 'User suspended and email sent.');
}

    public function activateUser(ActivateUserRequest $request, $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        
        DB::transaction(function () use ($user, $request) {
            $user->update([
                'status' => 'verified',
                'suspension_reason' => null,
                'suspended_until' => null,
            ]);

            // Log user activity
            UserActivityLog::create([
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
                'action_type' => 'activated',
                'reason' => $request->reason,
            ]);

            // Log admin action
            AdminActionLog::create([
                'admin_id' => auth()->id(),
                'action' => 'activated_user',
                'target_user_id' => $user->id,
                'target_type' => 'user',
                'target_id' => $user->id,
                'reason' => $request->reason,
            ]);
        });

        // Send email notification
        Mail::to($user->email)->send(new UserReactivated($user, $request->reason));
return back()->with('success', 'User activated successfully and email sent.');
    }

    public function getUserDetails($id)
    {
        $user = User::withCount(['jobPostings', 'productOffers'])
            ->with(['jobPostings', 'productOffers', 'activityLogs' => function($q) {
                $q->latest()->limit(5);
            }])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'user' => $user,
            'stats' => [
                'total_jobs' => $user->job_postings_count,
                'total_offers' => $user->product_offers_count,
                'approved_jobs' => $user->jobPostings()->where('status', 'approved')->count(),
                'approved_offers' => $user->productOffers()->where('status', 'approved')->count(),
                'pending_jobs' => $user->jobPostings()->where('status', 'pending')->count(),
                'pending_offers' => $user->productOffers()->where('status', 'pending')->count(),
            ]
        ]);
    }
}