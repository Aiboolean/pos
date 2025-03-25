<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System - Login</title>
    @vite('resources/css/app.css')
    <style>
         html, body {
            height: 100%;
            overflow: hidden; 
            margin: 0;
            padding: 0;
        }
        .auth-bg {
            background-image: url("{{ asset('storage/images/cupstreet login background.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .login-card {
            background-color: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .input-coffee {
            border-color: #d1d5db;
            transition: all 0.3s ease;
        }
        .input-coffee:focus {
            border-color: #9ca3af;
            box-shadow: 0 0 0 3px rgba(164, 122, 82, 0.25);
        }
        .btn-coffee {
            background-color: #6F4E37;
            transition: all 0.3s ease;
        }
        .btn-coffee:hover {
            background-color: #5a3c29;
        }
    </style>
</head>

<body class="auth-bg flex items-center justify-center min-h-screen p-4">
    @yield('content')
</body>
</html>