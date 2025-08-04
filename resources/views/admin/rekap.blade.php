<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Dokumen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex bg-gray-100 min-h-screen">

    @include('partials.sidebar-admin') <!-- Sidebar -->

    <main class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6 border-b pb-3 flex items-center gap-2">
            📋 Rekap Dokumen
        </h1>

        @if(session('success'))
            <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- SORTING FORM -->
        <div class="flex justify-between items-center mb-4">
            <form method="GET" action="{{ route('admin.rekap') }}" class="flex items-center gap-2 text-sm">
                <label for="sort">Urutkan:</label>

                <select name="sort" id="sort" class="border border-gray-300 rounded px-2 py-1">
                    <option value="transmittal_number" {{ request('sort') === 'transmittal_number' ? 'selected' : '' }}>Nomor Transmital</option>
                    <option value="transmittal_date" {{ request('sort') === 'transmittal_date' ? 'selected' : '' }}>Tanggal</option>
                </select>

                <select name="direction" class="border border-gray-300 rounded px-2 py-1">
                    <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Naik (A-Z / Tua)</option>
                    <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Turun (Z-A / Baru)</option>
                </select>

                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Terapkan</button>
            </form>
        </div>

<!-- TABEL REKAP -->
<div class="overflow-x-auto bg-white shadow rounded-lg">
    <table class="min-w-full divide-y divide-gray-300 text-sm">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="px-4 py-3 text-left">Nomor Transmital</th>
                <th class="px-4 py-3 text-left">No Dokumen</th>
                <th class="px-4 py-3 text-left">Dari</th>
                <th class="px-4 py-3 text-left">Ke</th>
                <th class="px-4 py-3 text-left">Tanggal</th>
                <th class="px-4 py-3 text-left">Fase</th> <!-- ✅ Tambahan -->
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Update Status</th>
                <th class="px-4 py-3 text-left">Hapus</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($rekaps as $rekap)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-semibold text-gray-700">
                        {{ $rekap->transmittal_number }}
                    </td>

                    <td class="px-4 py-3 text-blue-700 underline">
                        @if($rekap->details->first()?->document_path)
                            <a href="{{ asset('storage/' . $rekap->details->first()->document_path) }}" target="_blank">
                                {{ $rekap->details->first()->document_number ?? '-' }}
                            </a>
                        @else
                            {{ $rekap->details->first()->document_number ?? '-' }}
                        @endif
                    </td>

                    <td class="px-4 py-3">{{ $rekap->from_department }}</td>
                    <td class="px-4 py-3">{{ $rekap->to_department }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($rekap->transmittal_date)->format('d/m/Y') }}</td>

                    <td class="px-4 py-3">
                        {{ $rekap->details->first()?->fase ?? '-' }} <!-- ✅ Tambahan -->
                    </td>

                    <td class="px-4 py-3">
                        @if($rekap->status === 'Selesai')
                            <span class="inline-block px-2 py-1 bg-green-200 text-green-800 rounded text-xs">✔ Selesai</span>
                        @else
                            <span class="inline-block px-2 py-1 bg-yellow-200 text-yellow-800 rounded text-xs">⏳ {{ $rekap->status }}</span>
                        @endif
                    </td>

                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.rekap.updateStatus', $rekap->id) }}" class="flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <select name="status" class="border border-gray-300 rounded px-2 py-1 text-sm">
                                <option value="Pending" {{ $rekap->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Selesai" {{ $rekap->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                Update
                            </button>
                        </form>
                    </td>

                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.rekap.destroy', $rekap->id) }}" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-gray-500 py-6">Belum ada data rekap dokumen.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    </main>

</body>
</html>
