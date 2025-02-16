<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsDriver
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the authenticated user is a driver
        if (auth()->check() && auth()->user()->role === 'driver') {
            return $next($request);
        }

        // Redirect non-drivers to the home page or show an error
        return redirect('/')->with('error', 'You are not authorized to access this page.');
    }
}