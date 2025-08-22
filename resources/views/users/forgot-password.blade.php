<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - NexHRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #a8a5e2ff, #083c4dff);
            --card-bg: rgba(255, 255, 255, 0.1);
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
            color: #ddffdd;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
        }

        .error-message {
            color: #ffdddd;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Forgot Password</h2>

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class="success-message">
                <p>{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('users.forgot.submit') }}">
            @csrf
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100">Send OTP</button>
        </form>

        <div class="mt-3 text-center">
            <a href="{{ route('users.login') }}" class="text-light text-decoration-underline" style="font-size: 0.9rem;">
                Back to Login
            </a>
        </div>
    </div>
</body>
</html>
