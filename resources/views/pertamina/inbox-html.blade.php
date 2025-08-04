<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan Masuk - Pertamina</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r shadow hidden md:block">
        <div class="p-6 border-b">
            <h1 class="text-xl font-bold text-blue-700">PERTAMINA</h1>
        </div>
        <nav class="p-4 space-y-2">
            <a href="{{ route('pertamina.dashboard') }}" class="block text-gray-700 hover:text-blue-600">🏠 Dashboard</a>
            <a href="{{ route('pertamina.upload.form') }}" class="block text-gray-700 hover:text-blue-600">📤 Upload Dokumen</a>
            <a href="{{ route('pertamina.inbox') }}" class="block font-semibold text-blue-700 bg-blue-100 px-2 py-1 rounded">📥 Pesan Masuk</a>
            <a href="{{ route('pertamina.notifikasi') }}" class="block text-gray-700 hover:text-blue-600">🔔 Notifikasi</a>
            <form method="POST" action="{{ route('logout') }}" class="pt-4">
                @csrf
                <button class="text-red-500 hover:underline">🔒 Logout</button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">📥 Pesan Masuk dari Admin</h2>
            <button onclick="document.querySelector('aside').classList.toggle('hidden')" class="md:hidden text-gray-600 focus:outline-none">
                ☰
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @forelse($messages as $msg)
            <div class="bg-white border border-gray-200 p-6 rounded-lg shadow mb-6">
                <p><strong>Dari:</strong> {{ $msg->from_department }}</p>
                <p><strong>Judul:</strong> {{ $msg->title }}</p>
                <p><strong>Nomor Dokumen:</strong> {{ $msg->document_number }}</p>
                <p><strong>Revisi:</strong> {{ $msg->revision }}</p>

                <div class="mt-4">
                    <iframe src="{{ asset('storage/' . $msg->document_path) }}" class="w-full h-72 border rounded" frameborder="0"></iframe>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-12">
                📭 Tidak ada dokumen baru.
            </div>
        @endforelse
    </main>

</body>
</html>
