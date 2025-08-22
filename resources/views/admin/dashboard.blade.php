<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - NexHRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #a8a5e2ff, #687BA6);
            --card-bg: rgba(255, 255, 255, 0.1);
            --card-hover-bg: rgba(255, 255, 255, 0.25);
            --text-light: #ffffff;
            --text-dark: #687BA6;
            --transition-speed: 0.3s;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: var(--primary-gradient);
            font-family: 'Segoe UI', sans-serif;
            color: var(--text-light);
        }

        .sidebar {
            min-height: 100vh;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            padding: 20px;
            width: 250px;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .sidebar h3 {
            font-weight: bold;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: var(--text-light);
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            border-radius: 10px;
            transition: background-color var(--transition-speed);
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: var(--text-light);
        }

        .navbar-text {
            font-size: 1.1rem;
            font-weight: 500;
        }

        .btn-danger {
            font-weight: bold;
        }

        .card-custom {
            border-radius: 15px;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            transition: transform var(--transition-speed), background-color var(--transition-speed);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .card-text {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .text-white {
            color: var(--text-light) !important;
        }

        .text-dark {
            color: var(--text-dark) !important;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .badge {
            font-size: 0.85em;
            padding: 0.5em 0.75em;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h3>Admin Panel</h3>
            <a href="#" class="nav-link active" onclick="showSection('dashboard', this)">Dashboard</a>
            <a href="#" class="nav-link" onclick="showSection('attendance', this)">Attendance</a>
            <a href="#" class="nav-link" onclick="showSection('users', this)">Users</a>
            <a href="#" class="nav-link" onclick="showSection('tasks', this)">Tasks</a>
            <a href="#" class="nav-link" onclick="showSection('tasks', this)">
                <form method="POST" action="{{ route('admin.logout') }}" class="ms-auto">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg shadow-sm px-4 py-3">
                <div class="container-fluid">
                    <span class="navbar-text">Welcome, {{ auth('admin')->user()->name }}</span>

                </div>
            </nav>

            <!-- Dashboard Section -->
            <div id="dashboard" class="content-section active container-fluid mt-4">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card text-center card-custom text-white p-4" onclick="showSection('users', document.querySelector('.sidebar a:nth-child(3)'))" style="cursor:pointer;">
                            <h5 class="card-title">Users</h5>
                            <p class="card-text">{{ $usersCount ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center card-custom text-white p-4" onclick="showSection('tasks', document.querySelector('.sidebar a:nth-child(4)'))" style="cursor:pointer;">
                            <h5 class="card-title">Tasks</h5>
                            <p class="card-text">{{ $tasksCount ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center card-custom text-white p-4" onclick="showSection('attendance', document.querySelector('.sidebar a:nth-child(2)'))" style="cursor:pointer;">
                            <h5 class="card-title">Attendance</h5>
                            <p class="card-text">{{ $attendanceRecords->count() ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center card-custom text-white p-4">
                            <h5 class="card-title">Idle Users</h5>
                            <p class="card-text">{{ $idleUsersCount ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Section -->
            <div id="attendance" class="content-section container-fluid mt-4">
                <div class="card card-custom text-white p-4">
                    <h4 class="card-title mb-4">Attendance Records</h4>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Worked Hours</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendanceRecords as $record)
                                <tr>
                                    <td>{{ $record->user->name }}</td>
                                    <td>{{ $record->check_in->format('M d, Y') }}</td>
                                    <td>{{ $record->check_in->format('H:i:s') }}</td>
                                    <td>
                                        @if($record->check_out)
                                        {{ $record->check_out->format('H:i:s') }}
                                        @else
                                        <span class="badge bg-warning text-dark">Not Checked Out</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($record->worked_hours > 0)
                                        {{ $record->worked_hours }} hours
                                        @else
                                        <span class="badge bg-secondary">Not Calculated</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $record->status === 'present' ? 'warning' : ($record->status === 'completed' ? 'success' : 'secondary') }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Users Section -->
            <div id="users" class="content-section container-fluid mt-4">
                <div class="card card-custom text-white p-4">
                    <h4 class="card-title mb-4">User Portal</h4>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->department ?? 'N/A' }}</td>
                                    <td>{{ $user->designation ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-light text-dark"
                                            onclick="openAssignModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                            Assign Task
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tasks Section -->
            <div id="tasks" class="content-section container-fluid mt-4">
                <div class="card card-custom text-white p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Task Manager</h4>
                        <button class="btn btn-light text-dark" onclick="openTaskModal()">Create Task</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Deadline</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ Str::limit($task->description, 50) }}</td>

                                    <td>
                                        @switch($task->status)
                                        @case('unknown')
                                        <span class="badge bg-secondary">Unknown</span>
                                        @break
                                        @case('pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                        @break
                                        @case('in_progress')
                                        <span class="badge bg-info text-dark">In Progress</span>
                                        @break
                                        @case('completed')
                                        <span class="badge bg-success">Completed</span>
                                        @break
                                        @default
                                        <span class="badge bg-light text-dark">{{ ucfirst($task->status) }}</span>
                                        @endswitch
                                    </td>

                                    <td>
                                        @if ($task->assigned_to === null)
                                        <span class="badge bg-danger">Unassigned</span>
                                        @else
                                        <span class="badge bg-primary">{{ $task->user->name ?? 'User #' . $task->assigned_to }}</span>
                                        @endif
                                    </td>

                                    <td>{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M Y') : '—' }}</td>

                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-success save-btn"
                                                onclick="openEditModal('{{ $task->id }}', '{{ addslashes($task->title) }}', '{{ addslashes($task->description) }}', '{{ $task->deadline ? $task->deadline->format('Y-m-d') : '' }}')">
                                                Edit
                                            </button>

                                            <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this task?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Create Task Modal -->
            <div class="modal fade" id="taskModal" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('admin.tasks.store') }}" id="taskForm">
                        @csrf
                        <div class="modal-content card-custom text-white">
                            <div class="modal-header">
                                <h5 class="modal-title">Create New Task</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Task Title</label>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Task Title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control" placeholder="Task Description" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="deadline" class="form-label">Deadline (optional)</label>
                                    <input type="date" name="deadline" id="deadline" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-light text-dark">Create Task</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Edit Task Modal -->
            <div class="modal fade" id="editTaskModal" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" id="editTaskForm">
                        @csrf
                        @method('PUT')
                        <div class="modal-content card-custom text-white">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Task</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="task_id" id="edit-task-id">
                                <div class="mb-3">
                                    <label for="edit-title" class="form-label">Task Title</label>
                                    <input type="text" name="title" id="edit-title" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-description" class="form-label">Description</label>
                                    <textarea name="description" id="edit-description" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-deadline" class="form-label">Deadline (optional)</label>
                                    <input type="date" name="deadline" id="edit-deadline" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-light text-dark">Update Task</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Assign Task Modal -->
            <div class="modal fade" id="assignModal" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('admin.tasks.assign') }}" id="assignForm">
                        @csrf
                        <input type="hidden" name="assigned_to" id="assign-user-id">
                        <div class="modal-content card-custom text-white">
                            <div class="modal-header">
                                <h5 class="modal-title">Assign Task to <span id="assign-user-name"></span></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="assign-task-select" class="form-label">Select Task</label>
                                    <select name="task_id" id="assign-task-select" class="form-select" required>
                                        <option value="">Select a task</option>
                                        @foreach($tasks->where('assigned_to', null) as $task)
                                        <option value="{{ $task->id }}">{{ $task->title }} ({{ $task->status }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-light text-dark">Assign Task</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize CSRF token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Switch between dashboard sections
        function showSection(sectionId, linkElement) {
            document.querySelectorAll('.content-section').forEach(el => el.classList.remove('active'));
            document.getElementById(sectionId).classList.add('active');

            document.querySelectorAll('.sidebar .nav-link').forEach(link => link.classList.remove('active'));
            linkElement.classList.add('active');
        }

        // Enable save button when user is selected
        function enableSaveButton(selectElement) {
            const taskId = selectElement.dataset.taskId;
            const saveButton = document.querySelector(`.save-btn[data-task-id="${taskId}"]`);
            saveButton.disabled = !selectElement.value;
        }

        // Assign task via AJAX
        function assignTask(buttonElement) {
            const taskId = buttonElement.dataset.taskId;
            const selectElement = document.querySelector(`.assign-select[data-task-id="${taskId}"]`);
            const userId = selectElement.value;

            if (!userId) {
                alert('Please select a user first');
                return;
            }

            buttonElement.disabled = true;
            buttonElement.innerText = "Assigning...";

            $.ajax({
                url: "{{ route('admin.tasks.assign') }}",
                method: "POST",
                data: {
                    task_id: taskId,
                    assigned_to: userId
                },
                success: function(response) {
                    alert('Task assigned successfully');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error assigning task: ' + xhr.responseJSON.message);
                    buttonElement.disabled = false;
                    buttonElement.innerText = "Save";
                }
            });
        }

        // Open modal to create a new task
        function openTaskModal() {
            const modal = new bootstrap.Modal(document.getElementById('taskModal'));
            document.getElementById('taskForm').reset();
            modal.show();
        }

        // Open modal to assign a task to a user
        function openAssignModal(userId, userName) {
            const assignModal = new bootstrap.Modal(document.getElementById('assignModal'));
            document.getElementById('assign-user-name').textContent = userName;
            document.getElementById('assign-user-id').value = userId;
            assignModal.show();
        }

        function openEditModal(taskId, title, description, deadline) {
            const editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));

            // Fill the form fields
            document.getElementById('edit-task-id').value = taskId;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-description').value = description;
            document.getElementById('edit-deadline').value = deadline ? deadline : '';

            // Set form action dynamically
            document.getElementById('editTaskForm').action = `/admin/tasks/${taskId}`; // adjust route if needed

            editModal.show();
        }
    </script>
</body>

</html>