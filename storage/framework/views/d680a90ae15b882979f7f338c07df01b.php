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
    <?php echo $__env->make('partials.sidebar-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="flex-1 p-6 bg-white">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">📩 Dokumen Masuk dari Pertamina</h2>

        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-6">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $notifikasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $pdfPath = $item->document_path ? asset('storage/' . ltrim($item->document_path, '/')) : '';
                ?>

                <div class="bg-white shadow rounded-lg p-4 cursor-pointer hover:bg-gray-50 message-item"
                     data-id="<?php echo e($item->id); ?>"
                     data-title="<?php echo e($item->title); ?>"
                     data-from="<?php echo e($item->dari_departemen); ?>"
                     data-date="<?php echo e(\Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i')); ?>"
                     data-doc="<?php echo e($item->document_number); ?>"
                     data-rev="<?php echo e($item->revision); ?>"
                     data-notes="<?php echo e($item->notes); ?>"
                     data-tag="<?php echo e($item->tag_number ?? ''); ?>"
                     data-pdf="<?php echo e($pdfPath); ?>">
                    <div class="flex justify-between">
                        <div class="font-semibold"><?php echo e($item->dari_departemen); ?></div>
                        <div class="text-sm text-gray-500"><?php echo e(\Carbon\Carbon::parse($item->created_at)->diffForHumans()); ?></div>
                    </div>
                    <div class="font-medium text-gray-900"><?php echo e($item->title); ?></div>
                    <div class="text-sm text-gray-600">No: <?php echo e($item->document_number); ?> • Rev: <?php echo e($item->revision); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 text-center">Tidak ada dokumen baru.</p>
            <?php endif; ?>
        </div>
    </main>
</div>


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

            <form id="replyForm" method="POST" action="<?php echo e(route('admin.reply-doc')); ?>" enctype="multipart/form-data" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="transmital_detail_id" id="inputDetailId">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Vendor</label>
                        <select name="vendor_id" required class="mt-1 w-full border rounded px-3 py-2">
                            <option value="">-- Pilih Vendor --</option>
                            <?php $__currentLoopData = $vendor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($v->id); ?>"><?php echo e($v->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH C:\laragon\www\project\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>