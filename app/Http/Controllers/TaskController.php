<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
   public function __construct()
{
    $this->middleware(function ($request, $next) {
        if (Auth::guard('admin')->check() || Auth::guard()->check()) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    });
}


    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();

        // Check if the task is assigned to the current user
        if ($task->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $task->status = $request->status;
        $task->save();

        // Return JSON response for AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Task status updated successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'status' => 'unknown', // Default status
            'assigned_to' => null, // Initially unassigned
        ]);

        return redirect()->back()->with('success', 'Task created successfully.');
    }

    public function assign(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $task = Task::find($validated['task_id']);
        $task->assigned_to = $validated['assigned_to'];
        $task->status = 'pending'; // Change status when assigned
        $task->save();

        return redirect()->back()->with('success', 'Task assigned successfully.');
    }

    // NEW: Delete task functionality
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        // Admin can delete any task, users can only delete their own
        if (Auth::guard('admin')->check() || $task->assigned_to === Auth::id()) {
            $task->delete();
            return redirect()->back()->with('success', 'Task deleted successfully.');
        }

        return redirect()->back()->with('error', 'Unauthorized action.');
    }
public function update(Request $request, $id)
{
    $task = Task::findOrFail($id);
    
    // Authorization check - allow admin or task owner
    if (!Auth::guard('admin')->check() && $task->assigned_to !== Auth::id()) {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'deadline' => 'nullable|date',
    ]);

    $task->update($validated);

    return redirect()->back()->with('success', 'Task updated successfully.');
}

}