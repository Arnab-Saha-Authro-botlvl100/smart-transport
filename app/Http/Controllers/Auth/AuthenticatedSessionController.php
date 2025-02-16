<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
 
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();
    //     $request->session()->regenerate();
    
    //     // Redirect based on role
    //     $user = Auth::user();
    
    //     if ($user->role === 'admin') {
    //         return redirect()->intended('/admin/dashboard');
    //     } elseif ($user->role === 'passenger') {
    //         return redirect()->intended('/dashboard');
    //     } else {
    //         return redirect()->intended('/driver/dashboard'); // Default fallback
    //     }
    // }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Fetch the authenticated user
        $user = Auth::user();

        // Ensure role-based redirection
        switch ($user->role) {
            case 'admin':
                return redirect()->intended(route('admin.dashboard'));
            case 'driver':
                return redirect()->intended(route('driver.dashboard'));
            case 'passenger':
                return redirect()->intended(route('dashboard'));
            default:
                return redirect()->intended('/dashboard'); // Fallback
        }
    }

    

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
