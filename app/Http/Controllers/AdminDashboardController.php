<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Task;
use App\Models\Attendance;

class AdminDashboardController extends Controller
{
    public function index()
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $users = User::all();
        $tasks = Task::with('user')->get();
        $attendanceRecords = Attendance::with('user')
            ->orderBy('check_in', 'desc')
            ->take(20) // Show last 20 records
            ->get();

        // Calculate idle users (users with no assigned tasks or all tasks completed)
        $idleUsersCount = $users->filter(function($user) {
            $userTasks = $user->tasks;
            return $userTasks->isEmpty() || $userTasks->where('status', '!=', 'completed')->isEmpty();
        })->count();

        return view('admin.dashboard', [
            'users' => $users,
            'usersCount' => $users->count(),
            'idleUsersCount' => $idleUsersCount,
            'tasks' => $tasks,
            'tasksCount' => $tasks->count(),
            'attendanceRecords' => $attendanceRecords
        ]);
    }
}