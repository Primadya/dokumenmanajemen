<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Kesiapan Dokumen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100 min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md flex flex-col justify-between">
        <div class="p-6">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">📁 Admin Panel</h2>
            <nav class="space-y-4">
                <a href="{{ route('admin.dashboard') }}" class="block text-gray-700 hover:text-blue-600">📥 Notifikasi</a>
                <a href="{{ route('admin.rekap') }}" class="block text-gray-700 hover:text-blue-600">📋 Rekap Dokumen</a>
                <a href="{{ route('admin.users') }}" class="block text-gray-700 hover:text-blue-600">👤 Manajemen User</a>
                <a href="{{ route('admin.vendors') }}" class="block text-gray-700 hover:text-blue-600">🏢 Vendor</a>
                <a href="{{ route('admin.eps') }}" class="block text-gray-700 hover:text-blue-600">📎 EPS</a>
                <a href="{{ route('admin.doc.readiness') }}" class="block text-blue-700 font-bold">🗂️ Kesiapan Dokumen</a>
            </nav>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="p-6">
            @csrf
            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">🚪 Logout</button>
        </form>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">🗂️ Kesiapan Dokumen</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Search & Sort -->
        <form method="GET" action="{{ route('admin.doc.readiness') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="search" placeholder="Cari vendor, judul, tag number..." value="{{ request('search') }}" class="w-full border border-gray-300 rounded px-3 py-2" />
                <select name="sort" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Urutkan</option>
                    <option value="latest" {{ request('sort')=='latest' ? 'selected':'' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort')=='oldest' ? 'selected':'' }}>Terlama</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">🔍 Cari</button>
            </div>
        </form>

        <!-- Form Upload Dokumen -->
        <div class="bg-white p-6 rounded shadow mb-8">
            <h2 class="text-xl font-semibold mb-4">Upload Dokumen Baru</h2>
            <form action="{{ route('admin.doc.readiness.upload') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf

                <div>
                    <label for="vendor_id" class="block font-medium mb-1">Vendor</label>
                    <select id="vendor_id" name="vendor_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id')==$vendor->id ? 'selected':'' }}>
                                {{ $vendor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="document_title" class="block font-medium mb-1">Judul Dokumen</label>
                    <input type="text" id="document_title" name="document_title" required value="{{ old('document_title') }}" class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <div>
                    <label for="document_number" class="block font-medium mb-1">Nomor Dokumen</label>
                    <input type="text" id="document_number" name="document_number" required value="{{ old('document_number') }}" class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <div>
                    <label for="po_list_number" class="block font-medium mb-1">Nomor PO</label>
                    <input type="text" id="po_list_number" name="po_list_number" value="{{ old('po_list_number') }}" class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <div>
                    <label for="revision" class="block font-medium mb-1">Revisi</label>
                    <input type="text" id="revision" name="revision" required value="{{ old('revision') }}" class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <div>
                    <label for="tag_number" class="block font-medium mb-1">Tag Number</label>
                    <input type="text" id="tag_number" name="tag_number" required value="{{ old('tag_number') }}" class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <div>
                    <label for="fase" class="block font-medium mb-1">Fase</label>
                    <input type="number" id="fase" name="fase" required value="{{ old('fase') }}" class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <!-- 🔽 Tambahan Disiplin -->
                <div>
                    <label for="discipline" class="block font-medium mb-1">Disiplin</label>
                    <select id="discipline" name="discipline" required class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">-- Pilih Disiplin --</option>
                        <option value="Boiler" {{ old('discipline') == 'Boiler' ? 'selected' : '' }}>Boiler</option>
                        <option value="Elect&Inst" {{ old('discipline') == 'Elect&Inst' ? 'selected' : '' }}>Elect&amp;Inst</option>
                        <option value="Rotating" {{ old('discipline') == 'Rotating' ? 'selected' : '' }}>Rotating</option>
                        <option value="SWD" {{ old('discipline') == 'SWD' ? 'selected' : '' }}>SWD</option>
                        <option value="Tanki" {{ old('discipline') == 'Tanki' ? 'selected' : '' }}>Tanki</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="file_dokumen" class="block font-medium mb-1">File PDF</label>
                    <input type="file" id="file_dokumen" name="file_dokumen" accept="application/pdf" required class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded w-full">
                        📤 Upload Dokumen
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Dokumen -->
        <div class="bg-white p-6 rounded shadow overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200 table-auto">
                <thead class="bg-gray-200 text-gray-800 font-semibold">
                    <tr>
                        <th class="px-4 py-2 text-left">Vendor</th>
                        <th class="px-4 py-2 text-left">Judul</th>
                        <th class="px-4 py-2 text-left">Nomor Dokumen</th>
                        <th class="px-4 py-2 text-left">Nomor PO</th>
                        <th class="px-4 py-2 text-left">Revisi</th>
                        <th class="px-4 py-2 text-left">Tag</th>
                        <th class="px-4 py-2 text-left">Fase</th>
                        <th class="px-4 py-2 text-left">Disiplin</th> <!-- ✅ Kolom tambahan -->
                        <th class="px-4 py-2 text-left">Transmital</th>
                        <th class="px-4 py-2 text-left">Updated</th>
                        <th class="px-4 py-2 text-left">File</th>
                        <th class="px-4 py-2 text-left">Kirim</th>
                        <th class="px-4 py-2 text-left">Hapus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($documents as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $item->vendor_name }}</td>
                            <td class="px-4 py-2">{{ $item->document_title }}</td>
                            <td class="px-4 py-2">{{ $item->document_number }}</td>
                            <td class="px-4 py-2">{{ $item->po_list_number }}</td>
                            <td class="px-4 py-2">{{ $item->revision }}</td>
                            <td class="px-4 py-2">{{ $item->tag_number }}</td>
                            <td class="px-4 py-2">{{ $item->fase ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $item->discipline ?? '-' }}</td> <!-- ✅ Isi kolom disiplin -->
                            <td class="px-4 py-2">{{ $item->transmittal_number ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $item->updated_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                    Lihat PDF
                                </a>
                            </td>
                            <td class="px-4 py-2">
                                <form action="{{ route('admin.doc.readiness.send', $item->id) }}" method="POST">
                                    @csrf
                                    <select name="to_department" required class="border border-gray-300 rounded px-2 py-1 text-sm mb-1">
                                        <option value="">-- Pilih Tujuan --</option>
                                        @foreach($divisiPertamina as $divisi)
                                            <option value="{{ $divisi->division }}">{{ $divisi->division }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                        Kirim
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-2">
                                <form action="{{ route('admin.doc.readiness.delete', $item->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center text-gray-500 py-6">Belum ada dokumen tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
