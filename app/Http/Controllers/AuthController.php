<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show landing page
     */
    public function landing()
    {
        return view('landing');
    }

    /**
     * Show login page
     */
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return $user && method_exists($user, 'isAdmin') && $user->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard.home');
        }
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Sync expired subscriptions and deactivate user if their plan ran out
            \App\Models\UserSubscription::syncExpired();
            if ($user && method_exists($user, 'activeSubscription') && $user->role === \App\Models\User::ROLE_USER) {
                $sub = $user->activeSubscription();
                if (!$sub) {
                    $user->deactivateForExpiredSubscription();
                }
            }

            if ($user && method_exists($user, 'isActive') && ! $user->isActive()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $reason = $user->deactivated_reason ?: 'Akun Anda saat ini non-aktif. Hubungi admin untuk informasi lebih lanjut.';

                return back()->withErrors([
                    'email' => $reason ?: 'Akun Anda tidak aktif. Silakan perpanjang langganan.',
                ])->with('deactivated_reason', $user->deactivated_reason);
            }

            $request->session()->regenerate();

            if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard.home');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing');
    }
}
