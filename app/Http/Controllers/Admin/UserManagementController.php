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
use Illuminate\Support\Facades\Storage;

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
    
public function showUserDetails($id)
{
    $user = User::where('role', 'user')->findOrFail($id);
    return view('admin.show', compact('user')); // ← NOT 'admin.users.show'
}
/**
 * Update specific section of user profile
 */
public function updateSection(Request $request, $id, $section)
{
    $user = User::where('role', 'user')->findOrFail($id);

    $rules = [];
    $data = [];

    switch ($section) {
        case 'header':
            $rules = [
                'full_name' => 'required|string|max:255',
                'status' => 'required|in:pending,verified,active,suspended',
                'suspension_reason' => 'nullable|string',
                'suspended_until' => 'nullable|date',
                'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ];

            if (auth()->user()->isSuperAdmin()) {
                $rules['role'] = 'required|in:user,admin,superadmin';
            }

            $data = $request->validate($rules);

// Handle profile image
if ($request->hasFile('profile_image')) {
    // Delete old image
    if ($user->profile_image) {
        $oldPath = str_replace('storage/', 'public/', $user->profile_image);
        if (Storage::exists($oldPath)) {
            Storage::delete($oldPath);
        }
    }

        // Save like this:
        $path = $request->file('profile_image')->store('profile-images', 'public');
        $data['profile_image'] = $path; 
}

            if (!auth()->user()->isSuperAdmin()) {
                unset($data['role']);
            }

            $user->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Header section updated successfully.'
            ]);

        case 'basic':
            $rules = [
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone_country_code' => 'required|string',
                'phone' => 'required|string',
                'whatsapp_country_code' => 'nullable|string',
                'whatsapp' => 'nullable|string',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female,other',
            ];
            break;

        case 'address':
            $rules = [
                'country' => 'required|string',
                'state' => 'required|string',
                'city' => 'required|string',
                'zip_code' => 'required|string',
                'current_address' => 'required|string',
                'communication_address' => 'nullable|string',
            ];
            break;

        case 'professional':
            $rules = [
                'designation' => 'nullable|string',
                'company_name' => 'nullable|string',
                'industry_experience' => 'nullable|in:0-3,4-6,7-10,10+',
                'volunteer_interests' => 'nullable|string',
                'additional_info' => 'nullable|string',
            ];
            break;

        case 'identity':
            $rules = [
                'civil_id' => 'nullable|string|unique:users,civil_id,' . $user->id,
                'passport_number' => 'nullable|string|unique:users,passport_number,' . $user->id,
                'passport_expiry' => 'nullable|date',
                'residency_type' => 'nullable|in:Visit Visa,Work Visa,Residence Visa,Permanent Residence,Citizen',
                'residency_expiry' => 'nullable|date',
            ];
            break;

        case 'membership':
            $rules = [
                'status' => 'required|in:pending,verified,active,suspended',
                'suspension_reason' => 'nullable|string',
                'suspended_until' => 'nullable|date',
            ];
            break;

        default:
            return response()->json(['success' => false, 'message' => 'Invalid section'], 400);
    }

    // For non-header sections
    $data = $request->validate($rules);
    $user->update($data);

    return response()->json([
        'success' => true,
        'message' => ucfirst($section) . ' section updated successfully.'
    ]);
}

}