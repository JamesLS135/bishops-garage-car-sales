<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // First, check if the user is authenticated.
        if (!Auth::check()) {
            return redirect('login');
        }

        // Get the current user.
        $user = Auth::user();

        // Loop through the roles required by the route (e.g., 'Admin', 'Sales').
        // If the user's role matches any of the required roles, let them proceed.
        foreach ($roles as $role) {
            if ($user->role == $role) {
                return $next($request);
            }
        }

        // If the user does not have any of the required roles,
        // redirect them to the dashboard with an error message.
        return redirect('dashboard')->with('error', 'You do not have permission to access this page.');
    }
}
