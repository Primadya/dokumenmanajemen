@extends(Auth::user()->role === 'admin' ? 'layouts.admin' : 'layouts.pertamina')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Histori Dokumen</h2>

    <table class="w-full table-auto border">
        <thead class="bg-gray-200">
            <tr>
                <th class="border px-4 py-2">Nomor</th>
                <th class="border px-4 py-2">Judul</th>
                <th class="border px-4 py-2">Revisi</th>
                <th class="border px-4 py-2">Dari</th>
                <th class="border px-4 py-2">Ke</th>
                <th class="border px-4 py-2">Tanggal</th>
                <th class="border px-4 py-2">Status</th>
                <th class="border px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $doc)
            <tr class="bg-white">
                <td class="border px-4 py-2">{{ $doc->document_number }}</td>
                <td class="border px-4 py-2">{{ $doc->title }}</td>
                <td class="border px-4 py-2">{{ $doc->revision }}</td>
                <td class="border px-4 py-2">{{ $doc->from_department }}</td>
                <td class="border px-4 py-2">{{ $doc->to_department }}</td>
                <td class="border px-4 py-2">{{ $doc->transmital_date }}</td>
                <td class="border px-4 py-2">
                    @if($doc->is_read)
                        <span class="text-green-600 font-semibold">Dibaca</span>
                    @else
                        <span class="text-yellow-600 font-semibold">Belum Dibaca</span>
                    @endif
                </td>
                <td class="border px-4 py-2">
                    <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank" class="text-blue-500 underline">Lihat PDF</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection