<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActionLog; // ← Import the model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        $activityLogs = AdminActionLog::where('admin_id', $user->id)
            ->with(['targetUser', 'admin']) // ← Load both relations
            ->latest()
            ->take(5)
            ->get();

        return view('admin.profile.show', compact('user', 'activityLogs'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string',
            'designation' => 'nullable|string',
            'company_name' => 'nullable|string',
            'industry_experience' => 'nullable|in:0-3,4-6,7-10,10+',
            'volunteer_interests' => 'nullable|string',
            'additional_info' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'full_name', 'phone', 'designation', 'company_name',
            'industry_experience', 'volunteer_interests', 'additional_info'
        ]);

        // Handle profile image
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Admin profile updated successfully !');
    }
}