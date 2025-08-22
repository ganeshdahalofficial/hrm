<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Dashboard - NexHRM</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #a8a5e2ff, #083c4dff);
            --card-bg: rgba(255, 255, 255, 0.1);
            --card-hover-bg: rgba(255, 255, 255, 0.25);
            --text-light: #ffffff;
            --text-dark: #333333;
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

        .card-custom:hover {
            background: var(--card-hover-bg);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .card-text {
            font-size: 1.2rem;
            font-weight: 500;
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

        .attendance-status {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .btn-success,
        .btn-danger {
            transition: all 0.3s ease;
        }

        .btn-success:hover:not(:disabled),
        .btn-danger:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-success:disabled,
        .btn-danger:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        thead tr td:first-child {
   border-top-left-radius: 10px;
   border-top-right-radius: 10px;
}
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h3>User Panel</h3>
            <a href="#" class="nav-link active" onclick="showSection('dashboard', this)">Dashboard</a>
            <a href="#" class="nav-link" onclick="showSection('tasks', this)">Tasks</a>
            <a href="#" class="nav-link" onclick="showSection('profile', this)">Profile</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg shadow-sm px-4 py-3">
                <div class="container-fluid">
                    <span class="navbar-text" id="user-greeting">Welcome, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm ms-auto">Logout</button>
                    </form>
                </div>
            </nav>

            <!-- Dashboard Section -->
            <div id="dashboard" class="content-section active container-fluid mt-4">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="card text-center card-custom text-white p-4">
                            <h5 class="card-title">Tasks Assigned</h5>
                            <p class="card-text">{{ $taskStats['assigned'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center card-custom text-white p-4">
                            <h5 class="card-title">Completed</h5>
                            <p class="card-text">{{ $taskStats['completed'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center card-custom text-white p-4">
                            <h5 class="card-title">Pending</h5>
                            <p class="card-text">{{ $taskStats['pending'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center card-custom text-white p-4">
                            <h5 class="card-title">In Progress</h5>
                            <p class="card-text">{{ $taskStats['in_progress'] }}</p>
                        </div>
                    </div>
                </div>
                <!-- Check-in/Check-out Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-custom text-white p-4">
                            <h4 class="card-title mb-3 text-center">Attendance</h4>

                            <!-- Check-in/Check-out buttons -->
                            <div class="d-flex gap-3 mb-4" style="justify-content: center;">
                                <form method="POST" action="{{ route('attendance.checkin') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg"
                                        {{ $hasCheckedInToday ? 'disabled' : '' }}>
                                        <i class="bi bi-clock-fill me-2"></i>Check In
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('attendance.checkout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-lg"
                                        {{ !$hasCheckedInToday || $hasCheckedOutToday ? 'disabled' : '' }}>
                                        <i class="bi bi-clock-history me-2"></i>Check Out
                                    </button>
                                </form>
                            </div>
                            </div>

                            <!-- Today's attendance status -->
                            <div class="attendance-status d-flex flex-row align-items-center justify-content-center">
                                <h5>Today's Status:</h5>
                                @if($hasCheckedInToday)
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success">Checked In</span>
                                    <span>at {{ $todayAttendance->check_in->format('H:i:s') }}</span>
                                </div>

                                @if($hasCheckedOutToday)
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="badge bg-info">Checked Out</span>
                                    <span>at {{ $todayAttendance->check_out->format('H:i:s') }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="badge bg-primary">Worked: {{ $todayAttendance->worked_hours }} hours</span>
                                </div>
                                @endif
                                @else
                                <span class="badge bg-secondary">Not Checked In Yet</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Profile Section -->
            <!-- Profile Section -->
            <div id="profile" class="content-section container-fluid mt-4">
                <div class="card card-custom text-white p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Your Profile</h4>
                        <button class="btn btn-light text-dark" onclick="toggleEditMode()">Edit Details</button>
                    </div>

                    <form id="profileForm" method="POST" action="{{ route('users.profile.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control bg-transparent text-white" name="name"
                                    value="{{ Auth::user()->name }}" readonly id="nameField">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control bg-transparent text-white"
                                    value="{{ Auth::user()->email }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control bg-transparent text-white" name="department"
                                    value="{{ Auth::user()->department ?? 'Not set' }}" readonly id="departmentField">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Designation</label>
                                <input type="text" class="form-control bg-transparent text-white" name="designation"
                                    value="{{ Auth::user()->designation ?? 'Not set' }}" readonly id="designationField">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Joining Date</label>
                                <input type="date" class="form-control bg-transparent text-white" name="joining_date"
                                    value="{{ Auth::user()->joining_date ? Auth::user()->joining_date->format('Y-m-d') : '' }}"
                                    readonly id="joiningDateField">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control bg-transparent text-white" name="phone"
                                    value="{{ Auth::user()->phone ?? 'Not set' }}" readonly id="phoneField">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Gender</label>
                                <select class="form-select bg-transparent text-white" name="gender" disabled id="genderField">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ Auth::user()->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ Auth::user()->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ Auth::user()->gender == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-none" id="saveButtonContainer">
                            <button type="submit" class="btn btn-success me-2">Save Changes</button>
                            <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tasks Section -->
            <!-- Tasks Section -->
            <div id="tasks" class="content-section container-fluid mt-4">
                <div class="card card-custom text-white p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Task Manager</h4>
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
                                        <select class="form-select form-select-sm task-status"
                                            data-task-id="{{ $task->id }}"
                                            data-original-status="{{ $task->status }}"
                                            onchange="enableUpdateButton(this)">
                                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        <button class="btn btn-sm btn-outline-light update-btn"
                                            data-task-id="{{ $task->id }}"
                                            onclick="updateTaskStatus(this)"
                                            disabled>
                                            Save
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSection(sectionId, linkElement) {
            document.querySelectorAll('.content-section').forEach(el => el.classList.remove('active'));
            document.getElementById(sectionId).classList.add('active');

            document.querySelectorAll('.sidebar .nav-link').forEach(link => link.classList.remove('active'));
            linkElement.classList.add('active');
        }

        function getStatusColor(status) {
            switch (status) {
                case 'pending':
                    return 'warning text-dark';
                case 'in_progress':
                    return 'info text-dark';
                case 'completed':
                    return 'success';
                default:
                    return 'secondary';
            }
        }

        function enableUpdate(selectElement) {
            const row = selectElement.closest('tr');
            const button = row.querySelector('button');
            button.disabled = !selectElement.value;
        }

        function updateTaskStatus(taskId, buttonElement) {
            const row = buttonElement.closest('tr');
            const selectElement = row.querySelector('select');
            const newStatus = selectElement.value;

            if (!newStatus) {
                alert('Please select a status first');
                return;
            }

            buttonElement.disabled = true;
            buttonElement.innerText = "Updating...";

            // Get the CSRF token from the meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/tasks/${taskId}/status`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        buttonElement.innerText = "Updated";
                        setTimeout(() => {
                            // Update the status display
                            const statusCell = row.querySelector('td:nth-child(3)');
                            statusCell.innerHTML = `<span class="badge bg-${getStatusColor(newStatus)}">${newStatus}</span>`;

                            // Remove the update button
                            const actionCell = row.querySelector('td:nth-child(5)');
                            actionCell.innerHTML = '';
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Update failed');
                    }
                })
                .catch(err => {
                    console.error("Update error:", err);
                    alert("Failed to update task. Please try again.");
                    buttonElement.innerText = "Update";
                    buttonElement.disabled = false;
                });
        }
        // Enable update button when status changes
        function enableUpdateButton(selectElement) {
            const taskId = selectElement.dataset.taskId;
            const button = document.querySelector(`.update-btn[data-task-id="${taskId}"]`);
            const originalStatus = selectElement.dataset.originalStatus || selectElement.options[selectElement.selectedIndex].text;

            if (selectElement.value !== originalStatus) {
                button.disabled = false;
                button.classList.remove('btn-outline-light');
                button.classList.add('btn-primary');
            } else {
                button.disabled = true;
                button.classList.remove('btn-primary');
                button.classList.add('btn-outline-light');
            }
        }

        // Update task status
        function updateTaskStatus(buttonElement) {
            const taskId = buttonElement.dataset.taskId;
            const selectElement = document.querySelector(`.task-status[data-task-id="${taskId}"]`);
            const newStatus = selectElement.value;

            buttonElement.disabled = true;
            buttonElement.innerText = "Updating...";

            // Get the CSRF token from the meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/tasks/${taskId}/status`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Update the original status
                        selectElement.dataset.originalStatus = newStatus;

                        buttonElement.innerText = "Saved";
                        buttonElement.classList.remove('btn-primary');
                        buttonElement.classList.add('btn-outline-light');

                        setTimeout(() => {
                            buttonElement.innerText = "Save";
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Update failed');
                    }
                })
                .catch(err => {
                    console.error("Update error:", err);
                    alert("Failed to update task. Please try again.");
                    buttonElement.innerText = "Save";
                    buttonElement.disabled = false;
                });
        }

        // Profile edit functions
        function toggleEditMode() {
    const fields = ['nameField', 'departmentField', 'designationField', 'joiningDateField', 'phoneField', 'genderField'];
    const saveButtonContainer = document.getElementById('saveButtonContainer');

    fields.forEach(field => {
        const element = document.getElementById(field);
        if (element.tagName === 'SELECT') {
            element.disabled = false;
        } else {
            element.readOnly = false;
        }

        element.classList.remove('bg-transparent');
        element.classList.add('bg-dark');
    });

    saveButtonContainer.classList.remove('d-none');
    saveButtonContainer.classList.add('d-block');
}


        function cancelEdit() {
            toggleEditMode();
            // Reset form values if needed
            document.getElementById('profileForm').reset();
        }

        // Initialize original status for all task selects
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelects = document.querySelectorAll('.task-status');
            statusSelects.forEach(select => {
                select.dataset.originalStatus = select.value;
            });
        });
    </script>
</body>

</html>