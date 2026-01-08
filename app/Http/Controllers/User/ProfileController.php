<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\UserActivityLog;
use App\Models\User;
use App\Models\Referral;
use App\Models\ReferralInvite;

class ProfileController extends Controller
{
public function show()
{
    $user = Auth::user();

    // Handle COMMA-SEPARATED string (not JSON)
    $volunteerInterests = [];
    if (!empty($user->volunteer_interests)) {
        // Split by comma, trim whitespace, remove empty
        $volunteerInterests = array_filter(
            array_map('trim', explode(',', $user->volunteer_interests)),
            fn($item) => !empty($item)
        );
    }

    // Assign clean array back (for Blade)
    $user->volunteer_interests = $volunteerInterests;

    $activityLogs = UserActivityLog::where('user_id', $user->id)
        ->latest()
        ->limit(10)
        ->get();

    return view('user.profile.show', compact('user', 'activityLogs'));
}

public function update(Request $request)
{
    $user = Auth::user();

    // Determine which section is being updated by checking presence of key fields
    $isPersonal = $request->hasAny(['full_name', 'phone', 'date_of_birth']);
    $isAddress = $request->hasAny(['country', 'state', 'city', 'zip_code']);
    $isProfessional = $request->hasAny(['designation', 'company_name', 'industry_experience']);
    $isDocuments = $request->hasAny(['civil_id', 'passport_number', 'residency_type']) || $request->hasFile('civil_id_file');
    $isInterests = $request->hasAny(['volunteer_interests', 'additional_info']);
    $isProfileImage = $request->hasFile('profile_image');

    // Validation rules per section (only validate what's being updated)
    $rules = [];

    if ($isPersonal) {
        $rules = array_merge($rules, [
            'full_name' => 'required|string|max:255',
            'phone_country_code' => 'required|string|max:5',
            'phone' => 'required|string|max:20',
            'whatsapp_country_code' => 'nullable|string|max:5',
            'whatsapp' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
        ]);
    }

    if ($isAddress) {
        $rules = array_merge($rules, [
            'country' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'current_address' => 'required|string',
            'communication_address' => 'nullable|string',
        ]);
    }

    if ($isProfessional) {
        $rules = array_merge($rules, [
            'designation' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:200',
            'industry_experience' => 'nullable|string|max:1000',
        ]);
    }

    if ($isDocuments) {
        $rules = array_merge($rules, [
            'civil_id' => 'nullable|string|unique:users,civil_id,' . $user->id,
            'civil_id_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'passport_number' => 'nullable|string|unique:users,passport_number,' . $user->id,
            'passport_expiry' => 'nullable|date|after:today',
            'residency_type' => 'nullable|string|max:100',
            'residency_expiry' => 'nullable|date|after:today',
        ]);
    }

    if ($isInterests) {
        $rules = array_merge($rules, [
            'volunteer_interests' => 'nullable|string|max:1000',
            'additional_info' => 'nullable|string|max:2000',
        ]);
    }

    if ($isProfileImage) {
        $rules = array_merge($rules, [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    // Validate only the rules that apply
    $validated = $request->validate($rules);

    // Handle profile image
    if ($request->hasFile('profile_image')) {
        if ($user->profile_image && Storage::exists('public/' . $user->profile_image)) {
            Storage::delete('public/' . $user->profile_image);
        }
        $path = $request->file('profile_image')->store('profile-images', 'public');
        $validated['profile_image'] = $path;
    }

    // Handle civil ID file
    if ($request->hasFile('civil_id_file')) {
        if ($user->civil_id_file_path && Storage::exists('public/' . $user->civil_id_file_path)) {
            Storage::delete('public/' . $user->civil_id_file_path);
        }
        $path = $request->file('civil_id_file')->store('civil-documents', 'public');
        $validated['civil_id_file_path'] = $path;
    }

    // Update user
    if (!empty($validated)) {
        $user->update($validated);

        // Log activity
        UserActivityLog::create([
            'user_id' => $user->id,
            'action_type' => 'profile_updated',
            'reason' => 'Profile section updated',
        ]);
    }

    return response()->json(['message' => 'Profile updated successfully!']);
}

public function showDownline()
{
    $user = auth()->user();

    // Build full tree with YOU as root
    $tree = [
        'user' => $user,
        'level' => 0,
        'children' => $this->buildReferralTree($user->id, 1, 4) // max depth = 4
    ];

    return view('user.referral.downline', compact('tree'));
}

private function buildReferralTree($userId, $level, $maxLevel)
{
    if ($level > $maxLevel) {
        return [];
    }

    $referrals = Referral::with('referred')
        ->where('referrer_id', $userId)
        ->where('level', 1)
        ->get();

    return $referrals->map(function ($ref) use ($level, $maxLevel) {
        if (!$ref->referred) return null;

        return [
            'user' => $ref->referred,
            'level' => $level,
            'children' => $this->buildReferralTree($ref->referred->id, $level + 1, $maxLevel)
        ];
    })->filter()->values()->all();
}

public function sendInvite(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:100',
        'contact' => 'required|string|max:50',
        'type' => 'required|in:whatsapp,email',
    ]);

    $user = auth()->user();
    $contact = $request->contact;
    $type = $request->type;

    if ($type === 'email') {
        if (User::where('email', $contact)->exists()) {
            return response()->json([
                'error' => 'This email is already registered. They are already a member!'
            ], 422);
        }
    } else {
        // Clean phone: keep only digits
        $phoneDigits = preg_replace('/[^0-9]/', '', $contact);
        // Assume your `users.phone` stores only digits (no country code)
        // Adjust logic if you store full number
        if (strlen($phoneDigits) > 10) {
            $phoneDigits = substr($phoneDigits, -10);
        }
        if (User::where('phone', $phoneDigits)->exists()) {
            return response()->json([
                'error' => 'This phone number is already registered. They are already a member!'
            ], 422);
        }
    }

    // ✅ 2. Check if invite already sent
    $existingInvite = ReferralInvite::where('user_id', $user->id)
        ->where('contact', $contact)
        ->where('type', $type)
        ->first();

    if ($existingInvite) {
        $timeAgo = $existingInvite->created_at->diffForHumans();
        return response()->json([
            'error' => "You already sent an invite to this {$type} {$timeAgo}."
        ], 422);
    }

    // ✅ 3. Proceed to create & send
    $code = $user->referral_code?->code;
    if (!$code) {
        return response()->json(['error' => 'Your referral code is missing.'], 500);
    }

    $link = url("/register?ref=$code");

    ReferralInvite::create([
        'user_id' => $user->id,
        'name' => $request->name,
        'contact' => $contact,
        'type' => $type,
        'referral_code' => $code,
        'accepted' => false
    ]);

    // Send
    if ($type === 'whatsapp') {
        $text = urlencode("Hi {$request->name}! Join JobPortal via my link: $link");
        $url = "https://wa.me/{$contact}?text=$text";
        return response()->json(['redirect' => $url]);
    } else {
        try {
            \Mail::to($contact)->send(new \App\Mail\ReferralInviteMail($user, $request->name, $link));
            return response()->json(['message' => 'Invite sent successfully!']);
        } catch (\Exception $e) {
            \Log::error('Email send failed', [
                'contact' => $contact,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to send email. Please try again.'], 500);
        }
    }
}

public function viewInvites()
{
    $invites = ReferralInvite::where('user_id', auth()->id())
        ->latest()
        ->paginate(20);

    $stats = [
        'invited' => ReferralInvite::where('user_id', auth()->id())->count(),
        'accepted' => ReferralInvite::where('user_id', auth()->id())->where('accepted', true)->count(),
    ];

    return view('user.referral.invites', compact('invites', 'stats'));
}

public function resendInvite($id)
{
    $invite = ReferralInvite::where('user_id', auth()->id())->findOrFail($id);
    $link = url("/register?ref={$invite->referral_code}");

    if ($invite->type === 'whatsapp') {
        $text = urlencode("Reminder: Join JobPortal via my link: $link");
        // Can't auto-open WhatsApp in background — just show link
        return redirect()->back()->with('resend', "wa.me/{$invite->contact}?text=$text");
    } else {
        \Mail::to($invite->contact)->send(new \App\Mail\ReferralInviteMail(auth()->user(), $invite->name, $link));
        return back()->with('success', 'Invite re-sent!');
    }
}
}