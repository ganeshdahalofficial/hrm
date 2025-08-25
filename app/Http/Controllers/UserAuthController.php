<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;


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
        'department' => 'nullable|string|max:255',
        'designation' => 'nullable|string|max:255',
        'joining_date' => 'nullable|date',
        'phone' => 'nullable|string|max:255',
        'gender' => 'nullable|in:male,female,other',
    ]);
    
    $user->update($validated);
    
    return redirect()->back()->with('success', 'Profile updated successfully.');
}

   public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        // Check OTP stored in session
        if (!session()->has('reset_otp') || !session()->has('reset_user_id')) {
            return redirect()->route('users.forgot-password')
                             ->with('status', 'Session expired. Please try again.');
        }

        $otp = session('reset_otp');

        if ($request->otp != $otp) {
            return back()->withErrors(['otp' => 'Invalid OTP, please try again.']);
        }
        $createdAt = session('reset_otp_created_at');
if (now()->diffInMinutes($createdAt) > 5) {
    session()->forget(['reset_otp', 'reset_user_id', 'reset_otp_created_at']);
    return redirect()->route('users.forgot-password')->with('status', 'OTP expired. Please try again.');
}

        // OTP is correct → proceed to reset password
        return redirect()->route('users.reset-password');
    }
    public function sendOtp(Request $request)
    {
        // Validate email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Find user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User with this email does not exist.'])->withInput();
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Store OTP and user ID in session
        session([
            'reset_otp' => $otp,
            'reset_user_id' => $user->id,
            'reset_otp_created_at' => now(),
            'reset_user_email' => $user->email // Store email for verification
        ]);

        try {
            // Send OTP to user email
            Mail::to($user->email)->send(new SendOtpMail($otp));
            
            return redirect()->route('users.otp.form')
                         ->with('status', 'OTP has been sent to your email.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again.'])->withInput();
        }
    }

public function resetPassword(Request $request)
{
    $request->validate([
        'password' => 'required|string|min:6|confirmed',
    ]);

    if (!session()->has('reset_user_id')) {
        return response()->json([
            'message' => 'Session expired. Please try again.'
        ], 400);
    }

    $user = User::find(session('reset_user_id'));

    if (!$user) {
        return response()->json([
            'message' => 'User not found.'
        ], 404);
    }

    $user->password = bcrypt($request->password);
    $user->save();

    // Clear session
    session()->forget(['reset_user_id', 'reset_otp', 'reset_otp_created_at']);

    return response()->json([
        'message' => 'Password reset successful.'
    ]);
}


}