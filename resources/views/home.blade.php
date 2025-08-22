<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexHRM - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #a8a5e2ff, #083c4dff);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .container-box {
            text-align: center;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 50px;
            font-weight: bold;
        }

        .role-box {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 60px 40px;
            margin: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            min-width: 300px;
        }

        .role-box:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-5px);
        }

        .role-box h2 {
            font-size: 2rem;
            margin: 0;
        }

        .roles-wrapper {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>
    <div class="container-box">
        <h1>Welcome to NexHRM</h1>
        <div class="roles-wrapper">
            <div class="role-box" onclick="location.href='{{ route('admin.login') }}'">
                <h2>I'm him!</h2>
            </div>
            <div class="role-box" onclick="location.href='{{ route('users.login') }}'">
                <h2>I'm employee!</h2>
            </div>
        </div>
    </div>
</body>

</html>