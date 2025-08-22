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

// User Auth
Route::get('/users/login', [UserAuthController::class, 'showLoginForm'])->name('users.login');
Route::post('/users/login', [UserAuthController::class, 'login'])->name('users.login.submit');
Route::post('/users/logout', [UserAuthController::class, 'logout'])->name('users.logout');
Route::get('/users/signup', [UserAuthController::class, 'showSignupForm'])->name('users.signup');
Route::post('/users/signup', [UserAuthController::class, 'register'])->name('users.signup.submit');

// Admin Auth
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Attendance Routes
Route::middleware('auth')->group(function () {
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');
});

// Task Routes - FIXED: Added admin-specific routes
Route::middleware('auth')->group(function () {
    Route::post('/tasks/{id}/status', [TaskController::class, 'updateStatus'])->name('tasks.update');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/assign', [TaskController::class, 'assign'])->name('tasks.assign');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy'); // Added delete route
});

// Admin Task Routes - NEW: Separate routes for admin
Route::middleware('auth:admin')->group(function () {
    Route::post('/admin/tasks', [TaskController::class, 'store'])->name('admin.tasks.store'); // FIXED: This was missing
    Route::post('/admin/tasks/assign', [TaskController::class, 'assign'])->name('admin.tasks.assign');
    Route::delete('/admin/tasks/{id}', [TaskController::class, 'destroy'])->name('admin.tasks.destroy');
});

// Protected Dashboards
Route::middleware('auth')->group(function () {
    Route::get('/users/dashboard', [UserDashboardController::class, 'index'])->name('users.dashboard');
});

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/users/login');
})->name('logout');
// Attendance history route
Route::middleware('auth')->group(function () {
    Route::get('/attendance/history', [AttendanceController::class, 'getUserAttendance'])
        ->name('attendance.history');
});
Route::middleware('auth')->group(function () {
    // ... other routes
    Route::post('/users/profile/update', [UserAuthController::class, 'updateProfile'])->name('users.profile.update');
});


Route::put('/admin/tasks/{task}', [TaskController::class, 'update'])->name('admin.tasks.update');

