<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Attendance;
use Carbon\Carbon;

class UserDashboardController extends Controller
{
    public function index() {
        $user = Auth::user();
        
        // Get task statistics for the logged-in user
        $tasks = Task::where('assigned_to', $user->id)->get();
        
        $taskStats = [
            'assigned' => $tasks->count(),
            'completed' => $tasks->where('status', 'completed')->count(),
            'pending' => $tasks->where('status', 'pending')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
        ];

        // Get today's attendance
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in', today())
            ->first();

        $hasCheckedInToday = $todayAttendance !== null;
        $hasCheckedOutToday = $hasCheckedInToday && $todayAttendance->check_out !== null;

        return view('users.dashboard', compact(
            'user', 
            'tasks', 
            'taskStats',
            'todayAttendance',
            'hasCheckedInToday',
            'hasCheckedOutToday'
        ));
    }
}