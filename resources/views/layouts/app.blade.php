<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel App') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6; /* Light gray background */
        }
    </style>
</head>
<body class="text-gray-900">
    <nav class="bg-white shadow-md p-4 flex justify-between items-center">
        <div class="font-bold text-xl text-blue-600">Sistem Dokumen</div>
        <div>
            @auth
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button class="text-sm text-red-500 hover:underline">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <main class="container mx-auto py-6 px-4">
        @yield('content')
    </main>

    <footer class="bg-white shadow-md p-4 text-center">
        <p class="text-gray-600">© {{ date('Y') }} Sistem Dokumen. All rights reserved.</p>
    </footer>

    <script>
        // Optional: Add any JavaScript functionality here
    </script>
</body>
</html>