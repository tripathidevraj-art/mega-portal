<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle($request, Closure $next)
{
    if (auth()->check()) {
        $user = auth()->user();

        // Allow access only if verified (approved by admin)
        if ($user->role === 'user' && $user->status !== 'verified') {
            if ($user->status === 'pending') {
                return redirect()->route('profile.pending');
            }
            if ($user->status === 'suspended') {
                return redirect()->route('profile.suspended');
            }
            // For 'rejected' or others
            return redirect()->route('login')->with('error', 'Your account is not active.');
        }
    }

    return $next($request);
}
}
