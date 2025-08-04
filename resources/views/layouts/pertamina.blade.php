<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pertamina Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Ganti Vite dengan Tailwind CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-green-900 text-white p-5 space-y-4">
            <h1 class="text-2xl font-bold">PERTAMINA</h1>
            <nav class="space-y-2">
                <a href="{{ route('pertamina.dashboard') }}" class="block hover:underline">Dashboard</a>
                <a href="{{ route('pertamina.upload.form') }}" class="block hover:underline">Upload Dokumen</a>
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button class="text-red-400 hover:underline">Logout</button>
                </form>
            </nav>
        </aside>

        {{-- Konten --}}
        <main class="flex-1 p-6 bg-white">
            @yield('content')
        </main>
    </div>

</body>
</html>
