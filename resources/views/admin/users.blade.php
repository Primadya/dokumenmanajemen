<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengguna</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow flex flex-col justify-between h-full">
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">📁 Admin Panel</h2>
            <nav class="space-y-4">
                <a href="{{ route('admin.dashboard') }}" class="block text-gray-600 hover:text-blue-500">📥 Notifikasi</a>
                <a href="{{ route('admin.rekap') }}" class="block text-gray-600 hover:text-blue-500">📋 Rekap Dokumen</a>
                <a href="{{ route('admin.users') }}" class="block text-gray-800 font-bold">👤 Manajemen User</a>
                <a href="{{ route('admin.vendors') }}" class="block text-gray-600 hover:text-blue-500">🏢 Vendor</a>
                <a href="{{ route('admin.eps') }}" class="block text-gray-600 hover:text-blue-500">📎 EPS</a>
                <a href="{{ route('admin.doc.readiness') }}" class="block text-gray-600 hover:text-blue-500">🗂️ Kesiapan Dokumen</a>
            </nav>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="p-6">
            @csrf
            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">🚪 Logout</button>
        </form>
    </aside>

    <!-- Konten Utama -->
    <main class="flex-1 p-10 overflow-y-auto">
        <h1 class="text-2xl font-semibold mb-4">👤 Manajemen Pengguna</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 border border-green-400 px-4 py-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-800 border border-red-400 px-4 py-2 mb-4 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6">
            <form action="{{ route('admin.users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <div>
                    <label class="block mb-1 font-medium">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block mb-1 font-medium">Departemen</label>
                    <input type="text" name="division" value="{{ old('division') }}" class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div>
                    <label class="block mb-1 font-medium">Role</label>
                    <select name="role" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="PERTAMINA" {{ old('role') == 'PERTAMINA' ? 'selected' : '' }}>PERTAMINA</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Password</label>
                    <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Tambah User</button>
                </div>
            </form>
        </div>

        <h2 class="text-xl font-semibold mt-8 mb-4">📋 Daftar Pengguna</h2>
        <table class="w-full bg-white border rounded shadow text-sm">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-3 py-2">Nama</th>
                    <th class="border px-3 py-2">Email</th>
                    <th class="border px-3 py-2">Divisi</th>
                    <th class="border px-3 py-2">Role</th>
                    <th class="border px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="border px-3 py-2">{{ $user->name }}</td>
                    <td class="border px-3 py-2">{{ $user->email }}</td>
                    <td class="border px-3 py-2">{{ $user->division }}</td>
                    <td class="border px-3 py-2">{{ $user->role }}</td>
                    <td class="border px-3 py-2">
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-8">Belum ada data pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </main>

</body>
</html>
