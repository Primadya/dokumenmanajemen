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
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="block text-gray-700 hover:text-blue-600">📥 Notifikasi</a>
                <a href="<?php echo e(route('admin.rekap')); ?>" class="block text-gray-700 hover:text-blue-600">📋 Rekap Dokumen</a>
                <a href="<?php echo e(route('admin.users')); ?>" class="block text-gray-700 hover:text-blue-600">👤 Manajemen User</a>
                <a href="<?php echo e(route('admin.vendors')); ?>" class="block text-gray-700 hover:text-blue-600">🏢 Vendor</a>
                <a href="<?php echo e(route('admin.eps')); ?>" class="block text-gray-700 hover:text-blue-600">📎 EPS</a>
                <a href="<?php echo e(route('admin.doc.readiness')); ?>" class="block text-blue-700 font-bold">🗂️ Kesiapan Dokumen</a>
            </nav>
        </div>
        <form action="<?php echo e(route('logout')); ?>" method="POST" class="p-6">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">🚪 Logout</button>
        </form>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">🗂️ Kesiapan Dokumen</h1>

        <?php if(session('success')): ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Form Search & Sort -->
        <form method="GET" action="<?php echo e(route('admin.doc.readiness')); ?>" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="search" placeholder="Cari vendor, judul, tag number..." value="<?php echo e(request('search')); ?>" class="w-full border border-gray-300 rounded px-3 py-2" />
                <select name="sort" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Urutkan</option>
                    <option value="latest" <?php echo e(request('sort')=='latest' ? 'selected':''); ?>>Terbaru</option>
                    <option value="oldest" <?php echo e(request('sort')=='oldest' ? 'selected':''); ?>>Terlama</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">🔍 Cari</button>
            </div>
        </form>

        <!-- Form Upload Dokumen -->
        <div class="bg-white p-6 rounded shadow mb-8">
            <h2 class="text-xl font-semibold mb-4">Upload Dokumen Baru</h2>
            <form action="<?php echo e(route('admin.doc.readiness.upload')); ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php echo csrf_field(); ?>

                <div>
                    <label for="vendor_id" class="block font-medium mb-1">Vendor</label>
                    <select id="vendor_id" name="vendor_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">-- Pilih Vendor --</option>
                        <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($vendor->id); ?>" <?php echo e(old('vendor_id')==$vendor->id ? 'selected':''); ?>>
                                <?php echo e($vendor->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label for="document_title" class="block font-medium mb-1">Judul Dokumen</label>
                    <input type="text" id="document_title" name="document_title" required value="<?php echo e(old('document_title')); ?>" class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <div>
                    <label for="document_number" class="block font-medium mb-1">Nomor Dokumen</label>
                    <input type="text" id="document_number" name="document_number" required value="<?php echo e(old('document_number')); ?>" class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <div>
                    <label for="po_list_number" class="block font-medium mb-1">Nomor PO</label>
                    <input type="text" id="po_list_number" name="po_list_number" value="<?php echo e(old('po_list_number')); ?>" class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <div>
                    <label for="revision" class="block font-medium mb-1">Revisi</label>
                    <input type="text" id="revision" name="revision" required value="<?php echo e(old('revision')); ?>" class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <div>
                    <label for="tag_number" class="block font-medium mb-1">Tag Number</label>
                    <input type="text" id="tag_number" name="tag_number" required value="<?php echo e(old('tag_number')); ?>" class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <div>
                    <label for="fase" class="block font-medium mb-1">Fase</label>
                    <input type="number" id="fase" name="fase" required value="<?php echo e(old('fase')); ?>" class="w-full border border-gray-300 rounded px-3 py-2" />
                </div>

                <!-- 🔽 Tambahan Disiplin -->
                <div>
                    <label for="discipline" class="block font-medium mb-1">Disiplin</label>
                    <select id="discipline" name="discipline" required class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">-- Pilih Disiplin --</option>
                        <option value="Boiler" <?php echo e(old('discipline') == 'Boiler' ? 'selected' : ''); ?>>Boiler</option>
                        <option value="Elect&Inst" <?php echo e(old('discipline') == 'Elect&Inst' ? 'selected' : ''); ?>>Elect&amp;Inst</option>
                        <option value="Rotating" <?php echo e(old('discipline') == 'Rotating' ? 'selected' : ''); ?>>Rotating</option>
                        <option value="SWD" <?php echo e(old('discipline') == 'SWD' ? 'selected' : ''); ?>>SWD</option>
                        <option value="Tanki" <?php echo e(old('discipline') == 'Tanki' ? 'selected' : ''); ?>>Tanki</option>
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
                    <?php $__empty_1 = true; $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo e($item->vendor_name); ?></td>
                            <td class="px-4 py-2"><?php echo e($item->document_title); ?></td>
                            <td class="px-4 py-2"><?php echo e($item->document_number); ?></td>
                            <td class="px-4 py-2"><?php echo e($item->po_list_number); ?></td>
                            <td class="px-4 py-2"><?php echo e($item->revision); ?></td>
                            <td class="px-4 py-2"><?php echo e($item->tag_number); ?></td>
                            <td class="px-4 py-2"><?php echo e($item->fase ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($item->discipline ?? '-'); ?></td> <!-- ✅ Isi kolom disiplin -->
                            <td class="px-4 py-2"><?php echo e($item->transmittal_number ?? '-'); ?></td>
                            <td class="px-4 py-2"><?php echo e($item->updated_at->format('d/m/Y')); ?></td>
                            <td class="px-4 py-2">
                                <a href="<?php echo e(asset('storage/' . $item->file_path)); ?>" target="_blank" class="text-blue-600 hover:underline">
                                    Lihat PDF
                                </a>
                            </td>
                            <td class="px-4 py-2">
                                <form action="<?php echo e(route('admin.doc.readiness.send', $item->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <select name="to_department" required class="border border-gray-300 rounded px-2 py-1 text-sm mb-1">
                                        <option value="">-- Pilih Tujuan --</option>
                                        <?php $__currentLoopData = $divisiPertamina; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $divisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($divisi->division); ?>"><?php echo e($divisi->division); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                        Kirim
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-2">
                                <form action="<?php echo e(route('admin.doc.readiness.delete', $item->id)); ?>" method="POST" onsubmit="return confirm('Hapus dokumen ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="13" class="text-center text-gray-500 py-6">Belum ada dokumen tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
<?php /**PATH C:\laragon\www\project\resources\views/admin/doc_readiness.blade.php ENDPATH**/ ?>