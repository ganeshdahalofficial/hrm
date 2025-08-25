<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;

Route::get('/', fn() => view('home'))->name('home');

/*
|--------------------------------------------------------------------------
| User Auth
|--------------------------------------------------------------------------
*/
Route::get('/users/login', [UserAuthController::class, 'showLoginForm'])->name('users.login');
Route::post('/users/login', [UserAuthController::class, 'login'])->name('users.login.submit');
Route::post('/users/logout', [UserAuthController::class, 'logout'])->name('users.logout');
Route::get('/users/signup', [UserAuthController::class, 'showSignupForm'])->name('users.signup');
Route::post('/users/signup', [UserAuthController::class, 'register'])->name('users.signup.submit');

/*
|--------------------------------------------------------------------------
| Forgot Password Routes (FIXED)
|--------------------------------------------------------------------------
*/
Route::get('/users/forgot-password', function () {
    return view('users.forgot-password');
})->name('users.forgot-password');

// FIXED: Changed route name to avoid conflict and fixed controller method
Route::post('/users/forgot-password/send-otp', [UserAuthController::class, 'sendOtp'])->name('users.forgot.send-otp');

// Verify OTP
Route::get('/users/verify-otp', function () {
    return view('users.verify-otp');
})->name('users.otp.form');

Route::post('/users/verify-otp', [UserAuthController::class, 'verifyOtp'])->name('users.otp.submit');

// Reset Password
Route::get('/users/reset-password', function () {
    return view('users.reset-password');
})->name('users.reset-password');

Route::post('/users/reset-password', [UserAuthController::class, 'resetPassword'])->name('users.reset.submit');

/*
|--------------------------------------------------------------------------
| Admin Auth
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| User Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/users/dashboard', [UserDashboardController::class, 'index'])->name('users.dashboard');

    // Attendance
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
    Route::get('/attendance/history', [AttendanceController::class, 'getUserAttendance'])->name('attendance.history');

    // User tasks
    Route::post('/tasks/{id}/status', [TaskController::class, 'updateStatus'])->name('tasks.update');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/assign', [TaskController::class, 'assign'])->name('tasks.assign');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // User profile
    Route::post('/users/profile/update', [UserAuthController::class, 'updateProfile'])->name('users.profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth.admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Admin tasks
    Route::post('/admin/tasks', [TaskController::class, 'store'])->name('admin.tasks.store');
    Route::post('/admin/tasks/assign', [TaskController::class, 'assign'])->name('admin.tasks.assign');
    Route::delete('/admin/tasks/{id}', [TaskController::class, 'destroy'])->name('admin.tasks.destroy');
    Route::put('/admin/tasks/{task}', [TaskController::class, 'update'])->name('admin.tasks.update');
});

/*
|--------------------------------------------------------------------------
| Generic Logout (fallback)
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('users.login');
})->name('logout');