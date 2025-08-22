<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - NexHRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #a8a5e2ff, #083c4dff);
            --card-bg: rgba(255, 255, 255, 0.1);
            --card-hover-bg: rgba(255, 255, 255, 0.25);
            --text-light: #ffffff;
            --transition-speed: 0.3s;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: var(--primary-gradient);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            transition: transform var(--transition-speed);
        }

        .login-card h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 10px;
            color: var(--text-light);
        }

        .form-control::placeholder {
            color: #e0e0e0;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.3);
            box-shadow: none;
            color: var(--text-light);
        }

        .btn-primary {
            background-color: #00bfff;
            border: none;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #0099cc;
        }

        .text-danger {
            font-size: 0.85rem;
        }

        .success-message {
            font-size: 0.9rem;
            color: #00ffcc;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Reset Password</h2>

        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <form id="resetForm">
            @csrf
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="New Password" required>
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>
</body>
<script>
document.getElementById('resetForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const password = document.querySelector('input[name="password"]').value;
    const password_confirmation = document.querySelector('input[name="password_confirmation"]').value;

    try {
        const res = await fetch("{{ route('users.reset.submit') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ password, password_confirmation })
        });

        const data = await res.json();

        if (res.ok) {
            alert("Password reset successful. Redirecting to login...");
            window.location.href = "{{ route('users.login') }}";
        } else {
            // Handle session expired
            if (res.status === 400 && data.message.includes("Session expired")) {
                alert(data.message);
                window.location.href = "{{ route('users.forgot') }}";
                return;
            }

            // Handle validation errors
            if (res.status === 422 && data.errors) {
                const messages = Object.values(data.errors).flat().join("\n");
                alert(messages);
                return;
            }

            // General error fallback
            alert(data.message || "Reset failed");
        }
    } catch (err) {
        console.error("Reset error:", err);
        alert("Something went wrong. Please try again.");
    }
});
</script>

</html>
