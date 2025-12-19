<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\UserActivityLog;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $activityLogs = UserActivityLog::where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();
            
        return view('user.profile.show', compact('user', 'activityLogs'));
    }

public function update(Request $request)
{
    $user = Auth::user();
    
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'occupation' => 'nullable|string|max:100',
        'company' => 'nullable|string|max:200',
        'skills' => 'nullable|string|max:1000',
        'profile_image' => 'nullable|image|max:2048',
    ]);

    // Parse skills
    if ($request->filled('skills')) {
        $validated['skills'] = array_map('trim', explode(',', $request->skills));
    }

    // Handle profile image
    if ($request->hasFile('profile_image')) {
        // Delete old image if exists
        if ($user->profile_image && Storage::exists('public/' . $user->profile_image)) {
            Storage::delete('public/' . $user->profile_image);
        }
        
        $path = $request->file('profile_image')->store('profile-images', 'public');
        $validated['profile_image'] = $path;
    }

    $user->update($validated);

    // Log activity
    UserActivityLog::create([
        'user_id' => $user->id,
        'action_type' => 'profile_updated',
        'reason' => 'Profile information updated',
    ]);

    // YEH CHANGE KARO - JSON response ki jagah redirect
    return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
}
}