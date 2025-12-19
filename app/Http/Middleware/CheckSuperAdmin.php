<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Super admin privileges required.');
        }
        
        return $next($request);
    }
}