<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="flex min-h-screen">
    @include('partials.sidebar-admin')

    <main class="flex-1 p-6 bg-white">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">📩 Dokumen Masuk dari Pertamina</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse($notifikasi as $item)
                @php
                    $pdfPath = $item->document_path ? asset('storage/' . ltrim($item->document_path, '/')) : '';
                @endphp

                <div class="bg-white shadow rounded-lg p-4 cursor-pointer hover:bg-gray-50 message-item"
                     data-id="{{ $item->id }}"
                     data-title="{{ $item->title }}"
                     data-from="{{ $item->dari_departemen }}"
                     data-date="{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}"
                     data-doc="{{ $item->document_number }}"
                     data-rev="{{ $item->revision }}"
                     data-notes="{{ $item->notes }}"
                     data-tag="{{ $item->tag_number ?? '' }}"
                     data-pdf="{{ $pdfPath }}">
                    <div class="flex justify-between">
                        <div class="font-semibold">{{ $item->dari_departemen }}</div>
                        <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</div>
                    </div>
                    <div class="font-medium text-gray-900">{{ $item->title }}</div>
                    <div class="text-sm text-gray-600">No: {{ $item->document_number }} • Rev: {{ $item->revision }}</div>
                </div>
            @empty
                <p class="text-gray-500 text-center">Tidak ada dokumen baru.</p>
            @endforelse
        </div>
    </main>
</div>

{{-- Modal --}}
<div id="messageDetail" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg w-11/12 max-w-4xl max-h-screen overflow-auto">
        <div class="p-6">
            <h3 class="text-xl font-bold mb-4" id="msgSubject"></h3>

            <div class="flex justify-between mb-4">
                <span><strong>Dari:</strong> <span id="msgFrom"></span></span>
                <span><strong>Tanggal:</strong> <span id="msgDate"></span></span>
            </div>

            <p><strong>No Dokumen:</strong> <span id="msgDocNumber"></span></p>
            <p><strong>Rev:</strong> <span id="msgRevision"></span></p>
            <p class="mb-4"><strong>Catatan:</strong> <span id="msgNotes"></span></p>

            <div class="border rounded-lg mb-4 relative min-h-[400px]">
                <p class="text-sm text-gray-700 mb-2">
                    🔗 URL PDF:
                    <a id="debugPdfUrl" href="#" target="_blank" class="text-blue-600 underline break-all">-</a>
                </p>
                <object 
                    id="msgDocument"
                    data=""
                    type="application/pdf"
                    class="w-full h-96 border rounded"
                    aria-label="Preview Dokumen PDF"
                >
                    <p class="text-center mt-4 text-red-600">
                        ❌ PDF tidak dapat ditampilkan di browser ini.<br>
                        <a href="#" id="pdfFallbackLink" target="_blank" class="text-blue-600 underline">
                            Klik di sini untuk membuka PDF di tab baru
                        </a>
                    </p>
                </object>
            </div>

            <form id="replyForm" method="POST" action="{{ route('admin.reply-doc') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="transmital_detail_id" id="inputDetailId">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Vendor</label>
                        <select name="vendor_id" required class="mt-1 w-full border rounded px-3 py-2">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendor as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Judul Dokumen</label>
                        <input type="text" name="title" id="inputTitle" required class="mt-1 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Nomor Dokumen</label>
                        <input type="text" name="document_number" id="inputDocNumber" required class="mt-1 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Nomor PO</label>
                        <input type="text" name="po_number" id="inputPoNumber" required class="mt-1 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Revisi</label>
                        <input type="number" name="revision" id="inputRevision" required class="mt-1 w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Tag Number</label>
                        <input type="text" name="tag_number" id="inputTag" required class="mt-1 w-full border rounded px-3 py-2">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Upload File (PDF)</label>
                        <input type="file" name="document_file" accept="application/pdf" class="mt-1 block w-full border border-gray-300 rounded p-2">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Catatan</label>
                        <textarea name="notes" id="inputNotes" rows="3" class="mt-1 w-full border rounded px-3 py-2"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="reset" id="closeDetail" class="px-4 py-2 bg-gray-300 text-gray-800 rounded">Tutup</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Balas</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const objPdf = document.getElementById('msgDocument');
    const fallbackLink = document.getElementById('pdfFallbackLink');
    const debugPdfUrl = document.getElementById('debugPdfUrl');

    document.querySelectorAll('.message-item').forEach(el => {
        el.addEventListener('click', () => {
            const pdfUrl = el.dataset.pdf;

            document.getElementById('msgSubject').textContent = el.dataset.title;
            document.getElementById('msgFrom').textContent = el.dataset.from;
            document.getElementById('msgDate').textContent = el.dataset.date;
            document.getElementById('msgDocNumber').textContent = el.dataset.doc;
            document.getElementById('msgRevision').textContent = el.dataset.rev;
            document.getElementById('msgNotes').textContent = el.dataset.notes;

            document.getElementById('inputDetailId').value = el.dataset.id;
            document.getElementById('inputTitle').value = el.dataset.title;
            document.getElementById('inputDocNumber').value = el.dataset.doc;
            document.getElementById('inputRevision').value = el.dataset.rev;
            document.getElementById('inputTag').value = el.dataset.tag;
            document.getElementById('inputNotes').value = el.dataset.notes;

            if (pdfUrl) {
                const finalUrl = pdfUrl + `?t=${Date.now()}`;
                objPdf.data = finalUrl;
                fallbackLink.href = finalUrl;
                debugPdfUrl.href = finalUrl;
                debugPdfUrl.textContent = finalUrl;
            } else {
                objPdf.data = '';
                fallbackLink.href = '#';
                debugPdfUrl.href = '#';
                debugPdfUrl.textContent = 'Tidak tersedia';
            }

            document.getElementById('messageDetail').classList.remove('hidden');
        });
    });

    document.getElementById('closeDetail').addEventListener('click', () => {
        document.getElementById('messageDetail').classList.add('hidden');
        document.getElementById('replyForm').reset();
        objPdf.data = '';
        fallbackLink.href = '#';
        debugPdfUrl.href = '#';
        debugPdfUrl.textContent = '-';
    });
});
</script>

</body>
</html>
