<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Dokumen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex bg-gray-100 min-h-screen">

    <?php echo $__env->make('partials.sidebar-admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <!-- Sidebar -->

    <main class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6 border-b pb-3 flex items-center gap-2">
            📋 Rekap Dokumen
        </h1>

        <?php if(session('success')): ?>
            <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- SORTING FORM -->
        <div class="flex justify-between items-center mb-4">
            <form method="GET" action="<?php echo e(route('admin.rekap')); ?>" class="flex items-center gap-2 text-sm">
                <label for="sort">Urutkan:</label>

                <select name="sort" id="sort" class="border border-gray-300 rounded px-2 py-1">
                    <option value="transmittal_number" <?php echo e(request('sort') === 'transmittal_number' ? 'selected' : ''); ?>>Nomor Transmital</option>
                    <option value="transmittal_date" <?php echo e(request('sort') === 'transmittal_date' ? 'selected' : ''); ?>>Tanggal</option>
                </select>

                <select name="direction" class="border border-gray-300 rounded px-2 py-1">
                    <option value="asc" <?php echo e(request('direction') === 'asc' ? 'selected' : ''); ?>>Naik (A-Z / Tua)</option>
                    <option value="desc" <?php echo e(request('direction') === 'desc' ? 'selected' : ''); ?>>Turun (Z-A / Baru)</option>
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
            <?php $__empty_1 = true; $__currentLoopData = $rekaps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rekap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-semibold text-gray-700">
                        <?php echo e($rekap->transmittal_number); ?>

                    </td>

                    <td class="px-4 py-3 text-blue-700 underline">
                        <?php if($rekap->details->first()?->document_path): ?>
                            <a href="<?php echo e(asset('storage/' . $rekap->details->first()->document_path)); ?>" target="_blank">
                                <?php echo e($rekap->details->first()->document_number ?? '-'); ?>

                            </a>
                        <?php else: ?>
                            <?php echo e($rekap->details->first()->document_number ?? '-'); ?>

                        <?php endif; ?>
                    </td>

                    <td class="px-4 py-3"><?php echo e($rekap->from_department); ?></td>
                    <td class="px-4 py-3"><?php echo e($rekap->to_department); ?></td>
                    <td class="px-4 py-3"><?php echo e(\Carbon\Carbon::parse($rekap->transmittal_date)->format('d/m/Y')); ?></td>

                    <td class="px-4 py-3">
                        <?php echo e($rekap->details->first()?->fase ?? '-'); ?> <!-- ✅ Tambahan -->
                    </td>

                    <td class="px-4 py-3">
                        <?php if($rekap->status === 'Selesai'): ?>
                            <span class="inline-block px-2 py-1 bg-green-200 text-green-800 rounded text-xs">✔ Selesai</span>
                        <?php else: ?>
                            <span class="inline-block px-2 py-1 bg-yellow-200 text-yellow-800 rounded text-xs">⏳ <?php echo e($rekap->status); ?></span>
                        <?php endif; ?>
                    </td>

                    <td class="px-4 py-3">
                        <form method="POST" action="<?php echo e(route('admin.rekap.updateStatus', $rekap->id)); ?>" class="flex items-center gap-2">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <select name="status" class="border border-gray-300 rounded px-2 py-1 text-sm">
                                <option value="Pending" <?php echo e($rekap->status === 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                <option value="Selesai" <?php echo e($rekap->status === 'Selesai' ? 'selected' : ''); ?>>Selesai</option>
                            </select>
                            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                Update
                            </button>
                        </form>
                    </td>

                    <td class="px-4 py-3">
                        <form method="POST" action="<?php echo e(route('admin.rekap.destroy', $rekap->id)); ?>" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="text-center text-gray-500 py-6">Belum ada data rekap dokumen.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

    </main>

</body>
</html>
<?php /**PATH C:\laragon\www\project\resources\views/admin/rekap.blade.php ENDPATH**/ ?>