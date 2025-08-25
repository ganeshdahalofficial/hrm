<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - NexHRM</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #a8a5e2, #083c4d);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-family: Segoe UI, sans-serif;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 10px;
            color: #fff;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
            box-shadow: none;
        }

        .btn-primary {
            background: #00bfff;
            border: none;
            font-weight: bold;
        }

        .btn-primary:hover {
            background: #0099cc;
        }

        .signup-link {
            text-align: center;
            margin-top: 20px;
        }

        .signup-link a {
            color: #00bfff;
            text-decoration: underline;
        }

        .signup-link a:hover {
            color: #0099cc;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="text-center mb-4">Login</h2>
         @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('users.login.submit') }}">
    @csrf

    <!-- Email -->
    <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email"
               required value="{{ old('email') }}">
        @error('email')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        @error('password')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </div>

    <!-- Login Button -->
    <button type="submit" class="btn btn-primary w-100">Login</button>
</form>

        <div class="signup-link">
            <p>Don't have an account? <a href="{{ route('users.signup') }}">Sign Up</a></p>
        </div>
          <div class="signup-link">
            <p>Forgot Password? <a href="{{ route('users.forgot-password') }}">Forgot Password</a></p>
        </div>
    </div>
</body>
</html>