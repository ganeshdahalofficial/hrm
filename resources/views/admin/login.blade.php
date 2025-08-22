<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - NexHRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            background: linear-gradient(135deg, #a8a5e2ff, #083c4dff);
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
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
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
            text-align: center;
        }

        .login-card h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            font-weight: 700;
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
            box-shadow: none;
            background-color: rgba(255, 255, 255, 0.3);
        }

        .btn-primary {
            background-color: #00bfff;
            border: none;
            font-weight: bold;
            transition: background-color var(--transition-speed);
        }

        .btn-primary:hover {
            background-color: #0099cc;
        }

        .error-message {
            color: #ffdddd;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Admin Sign In</h2>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>
