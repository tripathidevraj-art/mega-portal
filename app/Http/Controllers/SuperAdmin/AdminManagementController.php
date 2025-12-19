<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = User::whereIn('role', ['admin', 'superadmin'])
            ->where('id', '!=', auth()->id()) // Exclude current user
            ->latest()
            ->paginate(10);
            
        return view('superadmin.admins.index', compact('admins'));
    }
    
    public function create()
    {
        return view('superadmin.admins.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,superadmin',
        ]);
        
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'verified',
            'email_verified_at' => now(),
        ]);
        
        return redirect()->route('superadmin.admins.index')
            ->with('success', 'Admin created successfully!');
    }
    
    public function destroy($id)
    {
        $admin = User::whereIn('role', ['admin', 'superadmin'])
            ->where('id', $id)
            ->firstOrFail();
            
        // Prevent deleting yourself
        if ($admin->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        
        $admin->delete();
        
        return back()->with('success', 'Admin deleted successfully!');
    }
}