<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->with('error', 'Akun Anda belum aktif.');
            }

            $request->session()->regenerate();

            return $user->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('pertamina.dashboard');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'required|string|unique:users,phone',
            'division'  => 'required|string|max:255',
            'role'      => 'required|in:admin,PERTAMINA',
            'password'  => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'division'  => $request->division,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
        ]);

        Auth::login($user);

        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('pertamina.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}
