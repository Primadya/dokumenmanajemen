<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pertamina Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            <div class="container mx-auto">
                <h2 class="text-2xl font-bold mb-6 text-center">Pesan Masuk dari Admin</h2>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="w-full">
                    @forelse($messages as $msg)
                        <div 
                            class="bg-white shadow rounded-lg p-4 mb-4 cursor-pointer hover:bg-gray-50 message-item"
                            data-id="{{ $msg->id }}"
                            data-pdf="{{ asset('storage/' . $msg->document_path) }}"
                            data-notes="{{ e($msg->notes) }}"
                        >
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold">{{ $msg->from_department }}</h3>
                                <span class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($msg->created_at)->diffForHumans() }}
                                </span>
                            </div>
                            <h4 class="font-medium text-gray-900">{{ $msg->title }}</h4>
                            <p class="text-sm text-gray-600 truncate">
                                No. Dokumen: {{ $msg->document_number }} • Revisi: {{ $msg->revision }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center">Tidak ada dokumen baru.</p>
                    @endforelse
                </div>
            </div>

            <!-- Message Detail Modal -->
            <div id="messageDetail" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg w-11/12 max-w-4xl max-h-screen overflow-auto">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-4" id="msgSubject"></h3>
                        <div class="flex justify-between mb-4">
                            <p><span class="font-semibold">Dari:</span> <span id="msgFrom"></span></p>
                            <p><span class="font-semibold">Tanggal:</span> <span id="msgDate"></span></p>
                        </div>
                        <p><span class="font-semibold">Nomor Dokumen:</span> <span id="msgDocNumber"></span></p>
                        <p><span class="font-semibold">Revisi:</span> <span id="msgRevision"></span></p>
                        <p class="mb-4"><span class="font-semibold">Catatan dari Pengirim:</span> <span id="msgNotes"></span></p>

                        <div class="border rounded-lg p-4 mb-4">
                            <embed id="msgDocument" src="" type="application/pdf" class="w-full h-96">
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a id="downloadDoc" href="#" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" download>Download PDF</a>
                            <button id="replyBtn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Balasan</button>
                            <button id="closeDetail" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reply Upload Modal -->
            <div id="replyUploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg w-11/12 max-w-2xl p-6 relative">
                    <h3 class="text-xl font-bold mb-4 text-center">Kirim Dokumen Balasan</h3>
                    <form method="POST" action="{{ route('pertamina.upload.store') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block mb-1 font-semibold">Nomor Transmital (Otomatis)</label>
                            <input type="text" name="transmittal_number" value="{{ $generatedTransmitalNumber ?? 'Auto' }}" class="border rounded w-full p-2 bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold">Nomor Dokumen</label>
                            <input type="text" name="document_number" id="replyDocumentNumber" class="border rounded w-full p-2" required>
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold">Judul Dokumen</label>
                            <input type="text" name="title" id="replyTitle" class="border rounded w-full p-2" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1 font-semibold">Revisi</label>
                                <input type="text" name="revision" class="border rounded w-full p-2" required>
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold">Status</label>
                                <select name="status" class="border rounded w-full p-2" required>
                                    <option value="approve">Approve</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold">Catatan</label>
                            <textarea name="notes" class="border rounded w-full p-2" rows="3"></textarea>
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold">Upload File PDF</label>
                            <input type="file" name="file" class="border rounded w-full p-2" accept="application/pdf" required>
                        </div>
                        <div class="flex justify-end space-x-4 pt-2">
                            <button type="button" id="cancelUploadReply" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Kirim Balasan</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.message-item').forEach(item => {
            item.addEventListener('click', function() {
                document.getElementById('msgSubject').textContent = this.querySelector('h4').textContent;
                document.getElementById('msgFrom').textContent = this.querySelector('h3').textContent;
                document.getElementById('msgDate').textContent = this.querySelector('span').textContent;
                document.getElementById('msgDocNumber').textContent = this.querySelector('p').textContent.split('No. Dokumen: ')[1].split(' •')[0];
                document.getElementById('msgRevision').textContent = this.querySelector('p').textContent.split('Revisi: ')[1];
                document.getElementById('msgNotes').textContent = this.getAttribute('data-notes') || '-';

                const pdfUrl = this.getAttribute('data-pdf');
                document.getElementById('msgDocument').setAttribute('src', pdfUrl);
                document.getElementById('downloadDoc').setAttribute('href', pdfUrl);

                document.getElementById('messageDetail').classList.remove('hidden');
            });
        });

        document.getElementById('closeDetail').addEventListener('click', function() {
            document.getElementById('messageDetail').classList.add('hidden');
        });

        document.getElementById('replyBtn').addEventListener('click', function () {
            document.getElementById('messageDetail').classList.add('hidden');

            document.getElementById('replyDocumentNumber').value = document.getElementById('msgDocNumber').textContent;
            document.getElementById('replyTitle').value = document.getElementById('msgSubject').textContent;

            document.getElementById('replyUploadModal').classList.remove('hidden');
        });

        document.getElementById('cancelUploadReply').addEventListener('click', function () {
            document.getElementById('replyUploadModal').classList.add('hidden');
            document.getElementById('messageDetail').classList.remove('hidden');
        });
    });
</script>
</body>
</html>
