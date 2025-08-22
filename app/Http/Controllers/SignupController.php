<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SignupController extends Controller
{
    public function showForm()
    {
        return view('users.signup');
    }

    public function signup(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6|confirmed',
            'department'   => 'nullable|string|max:255',
            'designation'  => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'phone'        => 'nullable|string|max:255',
            'gender'       => 'nullable|in:male,female,other',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        $user = User::create($validated);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
