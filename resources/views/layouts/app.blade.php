<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-white-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation bar with logo and link, with a thicker black border at the bottom -->
        <nav class="bg-[#f1eadc] text-black p-4">
            <div class="container mx-auto flex items-center w-full mb-2">
                <!-- Logo next to the "Products" link -->
                <img src="{{ asset('storage/product_images/cupstreet logo.png') }}" alt="Logo" class="h-8 mr-2">
                <a href="" class="text-lg font-bold">Cup Street Cafe</a>
            </div>
            <div class="w-full border-b-2 border-black"></div> <!-- Black line with space above it -->
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
