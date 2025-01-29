<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-gray-600 text-white p-4">
            <div class="container mx-auto">
                <a href="/" class="text-lg font-bold">Coffee Shop POS</a>
            </div>
        </nav>
        <main class="flex-grow">
            @yield('content')
        </main>
        <footer class="bg-gray-800 text-white p-4 text-center mt-6">
            &copy; {{ date('Y') }} Coffee Shop POS
        </footer>
    </div>
</body>
</html>
