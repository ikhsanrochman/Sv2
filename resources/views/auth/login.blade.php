<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Pelaporan Dosis Radiasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url('/img/login.png') center center / cover no-repeat;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem 2.5rem;
            width: 100%;
            max-width: 400px;
            color: white;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.2);
        }

        .form-control {
            background-color: transparent;
            border: none;
            border-bottom: 1px solid white;
            border-radius: 0;
            color: white;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #ddd;
        }

        .form-control::placeholder {
            color: #ccc;
        }

        .form-label {
            font-weight: bold;
            margin-top: 1rem;
        }

        .form-check-label,
        .forgot-password {
            font-size: 0.875rem;
        }

        .btn-login {
            background-color: white;
            color: #003366;
            border-radius: 999px;
            font-weight: bold;
        }

        .btn-login:hover {
            background-color: #e0e0e0;
        }

        .input-group-text {
            background-color: transparent;
            border: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h4 class="text-center fw-bold mb-4">Login</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus>
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <a href="#" class="forgot-password text-light text-decoration-underline">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-login w-100">Login</button>
        </form>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
