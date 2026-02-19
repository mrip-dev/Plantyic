<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::guard('admin')->user();
        if ($user->getRoleNames()->isEmpty()) {
            Auth::guard('admin')->logout();
        }
        if (!$user->hasRole(['customer'])) {
            return $next($request);
        } elseif ($user->hasRole(['customer'])) {
            return redirect()->route('account.dashboard');
        }
        abort(403, 'You are not authorized to access this page.');
    }
}
