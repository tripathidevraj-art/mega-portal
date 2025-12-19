<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Unauthorized access. Admin privileges required.');
        }
        
        return $next($request);
    }
}