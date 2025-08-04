<!-- resources/views/admin/partials/upload-doc-form.blade.php -->
<div class="bg-white p-6 rounded shadow mb-8">
    <h2 class="text-xl font-semibold mb-4">📄 Upload Dokumen Baru</h2>

    <form action="{{ route('admin.doc.readiness.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-1 font-medium">Nama Vendor</label>
                <select name="vendor_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">-- Pilih Vendor --</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">Judul Dokumen</label>
                <input type="text" name="document_title" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 font-medium">Nomor Dokumen</label>
                <input type="text" name="document_number" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 font-medium">Nomor PO List</label>
                <input type="text" name="po_list_number" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 font-medium">Revisi</label>
                <input type="text" name="revision" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 font-medium">Tag Number</label>
                <input type="text" name="tag_number" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 font-medium">Upload File (PDF)</label>
                <input type="file" name="file_dokumen" accept="application/pdf" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
        </div>

        <div class="text-right mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">Upload</button>
        </div>
    </form>
</div>
