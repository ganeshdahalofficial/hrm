<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserAuthController extends Controller
{
    public function showLoginForm() {
        if (Auth::check()) {
        return redirect()->route('users.dashboard');
    }
        return view('users.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('users.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('users.login'));
    }

    public function showSignupForm() {
        return view('users.signup');
    }

    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'phone' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'department' => $validated['department'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'joining_date' => $validated['joining_date'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect(route('users.dashboard'));
    }
    public function updateProfile(Request $request)
{
    $user = Auth::user();
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'department' => 'nullable|string|max:255',
        'designation' => 'nullable|string|max:255',
        'joining_date' => 'nullable|date',
        'phone' => 'nullable|string|max:255',
        'gender' => 'nullable|in:male,female,other',
    ]);
    
    $user->update($validated);
    
    return redirect()->back()->with('success', 'Profile updated successfully.');
}
}