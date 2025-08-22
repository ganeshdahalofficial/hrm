@if ($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - NexHRM</title>
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
            max-width: 700px; /* wider to fit 2 columns */
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 10px;
            color: #fff;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
            box-shadow: none;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-primary {
            background: #00bfff;
            border: none;
            font-weight: bold;
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            margin-top: 10px;
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

        .password-toggle {
            cursor: pointer;
            position: absolute;
                right: 22px;
    top: 75%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
            font-size: 18px;
            line-height: 1;
        }

        .password-input-group {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2 class="text-center mb-4">Sign Up</h2>
        <form method="POST" action="{{ route('users.signup.submit') }}">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" required>
                </div>

                <div class="col-md-6 mb-3 password-input-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                    <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
                </div>

                <div class="col-md-6 mb-3 password-input-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                    <span class="password-toggle" onclick="togglePassword('password_confirmation')">👁️</span>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="department" class="form-label">Department</label>
                    <input type="text" class="form-control" name="department" id="department" value="{{ old('department') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="designation" class="form-label">Designation</label>
                    <input type="text" class="form-control" name="designation" id="designation" value="{{ old('designation') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="joining_date" class="form-label">Joining Date</label>
                    <input type="date" class="form-control" name="joining_date" id="joining_date" value="{{ old('joining_date') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" name="gender" id="gender">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
        <div class="signup-link">
            <p>Already have an account? <a href="{{ route('users.login') }}">Login</a></p>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        }
    </script>
</body>
</html>
