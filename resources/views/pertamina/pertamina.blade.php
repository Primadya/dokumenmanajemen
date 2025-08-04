<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pertamina Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 h-screen bg-white shadow-md p-4">
            <h1 class="text-xl font-bold mb-6">PERTAMINA</h1>
            <ul class="space-y-4">
                <li><a href="{{ route('pertamina.dashboard') }}" class="text-blue-600">Dashboard</a></li>
                <li><a href="{{ route('pertamina.upload.form') }}" class="text-blue-600">Upload Dokumen</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-red-600">Logout</button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            @yield('content')
        </div>
    </div>

</body>
</html>