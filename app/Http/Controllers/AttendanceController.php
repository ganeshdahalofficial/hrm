<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkIn(Request $request)
    {
        $user = Auth::user();

        // Get today's record or create new
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('created_at', today()) // use created_at to find today's record
            ->first();

        if ($attendance) {
            return redirect()->back()->with('error', 'You have already checked in today');
        }

        Attendance::create([
            'user_id' => $user->id,
            'check_in' => now(),
            'status' => 'present',
            'worked_hours' => 0
        ]);

        return redirect()->back()->with('success', 'Check-in successful at ' . now()->format('H:i:s'));
    }


    public function checkOut(Request $request)
    {
        $user = Auth::user();

        // Get today's attendance record
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in', today())
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'You need to check in first');
        }

        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'You have already checked out today');
        }

        // Use Carbon for correct timezone
        $checkInTime = Carbon::parse($attendance->check_in);
        $checkOutTime = now();
        $workedHours = $checkOutTime->diffInMinutes($checkInTime) / 60;

        $attendance->update([
            'check_out' => $checkOutTime,
            'status' => 'Checked-out',
            'worked_hours' => round($workedHours, 2)
        ]);


        return redirect()->back()->with('success', 'Check-out successful at ' . $checkOutTime->format('H:i:s') . '. Worked: ' . round($workedHours, 2) . ' hours');
    }

    public function getUserAttendance($userId = null)
    {
        $userId = $userId ?? Auth::id();

        return Attendance::where('user_id', $userId)
            ->orderBy('check_in', 'desc')
            ->get();
    }
}
